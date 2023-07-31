<template>
    <div class="row">
        <div v-show="showCheckout" class="col-lg-8 cart_products">
            <div
                v-for="(group, key) in groupItemsByCurrier"
                :key="key"
            >
                <div v-show="!ff()">
                    <div>Courier: {{key === 'true' ? 'Tuffnells' : 'DPD'}}</div>
                </div>
                <ul class="cart_products">
                    <shopping-cart-tile
                        v-if="item.type === 'cart_item'"
                        v-for="(item, index) in group"
                        :key="index"
                        :item="item"
                        @selectedSize="setSelectedData"
                    />

                </ul>
            </div>
        </div>

        <div v-show="dataLoaded && items.length < 1" class="col-lg-8">
            <h4>Sorry, your cart is empty</h4>
        </div>
        <div v-show="!showCheckout" class="col-lg-8">
            <steps-form
            :ff=ff
            :delivery="isFreeDelivery || freeShipping ? 0 : delivery"
            :delivery-date="deliveryDate"
            :vat="(calculateTotal()/6).toFixed(2)"
            :sage-pay-merchant-session-key="sagePayMerchantSessionKey" />
        </div>
        <div :class="{'mt-order-details': !showCheckout && !ff()}" class="col-lg-4">
            <div class="cart_details">
                <h3 class="cart_details_heading">Order details</h3>
                <div class="cart_line">
                    <h4 class="cart_line_title">Subtotal</h4>
                    <p class="cart_line_value">&pound;{{ calculateSubtotal().toFixed(2) }}</p>
                </div>
                <div class="cart_group_mini">
                    <h4 class="cart_line_title">Delivery date</h4>
                    <div class="cart_line_input">
                        <div class="cart_group_mini_input">
                            <date-picker
                                v-model="deliveryDate"
                                :editable=false
                                format="DD/MM/YYYY"
                                value-type="DD/MM/YYYY"
                                @change="onDate"
                                :disabled-date="beforeTodayOrSunday"
                                placeholder="Please select date"
                                class="cart_group_field dark-field"
                            ></date-picker>
                            <img src="/assets/frontend/images/calendar_ico.png" alt="calendar icon" class="cart_icon">
                        </div>
                    </div>
                </div>
                <div class="cart_line delivery">
                    <h4 class="cart_line_title">Delivery</h4>
                    <p class="cart_line_value">
                        <span :class="{ old_price: isFreeDelivery || freeShipping}">&pound;{{ delivery.toFixed(2) }}</span>
                        <span v-show="isFreeDelivery || freeShipping" class="free">FREE</span>
                    </p>
                </div>
                <div class="cart_group_mini">
                    <h4 class="cart_line_title">Add a promotion code</h4>
                    <div class="cart_group_mini_input dark-field">
                        <input v-model="promoCode" type="text" name="promocode" class="cart_group_field promo_code">
                        <button @click="usePromoCode" type="button" class="cart_group_button">Update</button>
                    </div>
                </div>
                <div class="cart_line">
                    <h4 class="cart_line_title">Discount(s)</h4>
                    <p class="cart_line_value">{{error ? 'Wrong code!' : '('+promoValue+')'}}</p>
                </div>
                <div v-show="ff()" class="cart_line">
                    <h4 class="cart_line_title">VAT</h4>
                    <p class="cart_line_value">&pound;{{ (calculateTotal()/6).toFixed(2) }}</p>
                </div>
                <div class="cart_line total">
                    <h4 class="cart_line_title">Total</h4>
                    <p class="cart_line_value">&pound;{{ isFreeDelivery ? subtotal.toFixed(2) : calculateTotal() }}</p>
                </div>
            </div>
            <div class="cart_info">
                <button type="button" class="cta" @click="toggleCheckout">{{showCheckout ? 'CONTINUE' : 'BACK TO CART'}}</button>
                <slot name="cartCopy"></slot>
            </div>
        </div>
    </div>
</template>

<script>
    import { EventBus } from './../event-bus.js';
    import cartServices from './../services/cartServices.js';
    import {sagePayMerchantSessionKeyMixin} from './../mixins/sagePayMixins.js';
    import {calculateSubtotalMixin, getCartMixin, calculateTotalMixin} from './../mixins/cartMixins.js';
    import {removeCartItemMixin, getDeliveryCost} from './../mixins/cartHelperMixins'
    import {isFF} from './../mixins/helpers.js';
    import {usePromoCodeMixin} from './../mixins/promoCodeMixins.js';
    import DatePicker from 'vue2-datepicker';
    import 'vue2-datepicker/index.css';

    export default {
        name: 'shopping-cart',
        components: {
            DatePicker
        },
        mixins: [
            calculateSubtotalMixin,
            getCartMixin,
            calculateTotalMixin,
            isFF,
            usePromoCodeMixin,
            sagePayMerchantSessionKeyMixin,
            removeCartItemMixin,
            getDeliveryCost,
        ],
        data: () => ({
            items: [],
            subtotal: '',
            showCheckout: true,
            promoCode: '',
            dataLoaded: false,
            sizes: [],
            deliveryDate: '',
        }),
        beforeMount() {
            this.getCart();
        },
        mounted() {
            EventBus.$on('cart-changed', (items) => {
                this.items = items
            });
            EventBus.$on('cart-item-changed', (data) => {
                let newItem = data.newItems.find(function (item) {
                    return item.product_id === data.newItemId;
                });

                let oldItem = this.items.find(function (item) {
                    return item.product_id === data.oldItemId;
                });

                //find and remove double items
                for (let key in this.items) {
                    if(this.items[key].product_id === newItem.product_id){
                        this.items.splice(this.items.indexOf(this.items[key]), 1);
                    }
                }

                //remove old cart item and add new one
                this.items.splice(this.items.indexOf(oldItem), 1, newItem);

                localStorage.removeItem(oldItem.product_id);
                this.destroyCartItem(oldItem.id);
            });
            EventBus.$on('cart-error', () => {
                this.error = true
            });
        },
        computed:{
            groupItemsByCurrier() {
                return _.groupBy(this.items.filter(
                    i => i.type == 'cart_item'
                ), 'use_tuffnells');
            }
        },
        methods: {
            beforeTodayOrSunday(date) {
                return date < new Date() || date.getDay() === 0;
            },
            setSelectedData(data) {
                let index =_.findIndex(this.sizes, ['product_id', data.oldProductId]);

                const saveData = {
                    size: data.size,
                    product_id: data.productId
                };

                index === -1 ? this.sizes.push(saveData) : this.sizes[index] = saveData;
                return localStorage.setItem('sizes', JSON.stringify(this.sizes));
            },
            async destroyCartItem(id) {
                const {} = await cartServices.delete(id)
            },
            toggleCheckout() {
                if (!this.deliveryDate) {
                    return alert('Please select a delivery date before proceeding.');
                }

                this.showCheckout = !this.showCheckout;
            },
            isCartEmpty() {
                return this.dataLoaded && this.items.length > 0;
            },
            onDate (dd){
                let date = new Date(dd.slice(6, 10)+'-'+dd.slice(3, 5)+'-'+dd.slice(0, 2));
                let day = date.getDay();
                if(day === 6) {
                    this.tuffnellsItemDeliveryCost = 50;
                } else {
                    this.tuffnellsItemDeliveryCost = 15;
                }
                this.getDeliveryCost();
            }
        },
    }
</script>
<style lang="scss">
    .dark-field {
        .mx-icon-calendar {
            display: none;
        }
        input {
            background-color: #edece7;
            border: none;
            border-radius: unset;
            box-shadow: unset;
            font-size: 14px;
            font-family: "Muli-Light", sans-serif;
        }
    }
    .cart_group_mini {
        margin-top: 18px;
        border-bottom: 1px solid #f2f1ec;
        padding-bottom: 0;

        &_input {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
            padding-left: 0;
        }
    }
    .promo_code {
        width: 100%;
        height: 34px;
        padding: 6px 6px 6px 10px;
        margin-right: 20px;
    }
</style>
