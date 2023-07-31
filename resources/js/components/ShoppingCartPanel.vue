<template>
    <div class="dropdown_wrap js-dropdown">
        <div class="nav_bar_right_cart js-dropdown-title">
            <i class="fas fa-shopping-basket"></i>
            <div
                v-if="items.length"
                class="nav_bar_right_cart_count">
                {{ getCartCount < 100 ? getCartCount : "99+" }}
            </div>
        </div>
        <div class="cart_wrap dropdown_container" v-show="items.length">
            <div class="cart_header">
                <span class="cart_header_title">Shopping List</span>
                <span class="cart_header_close js-dropdown-close">close</span>
            </div>
            <div class="cart_container">
                <ul>
                    <shopping-cart-panel-item
                        v-if="item.type == 'cart_item'"
                        v-for="(item, index) in items"
                        :key="index"
                        :item="item"/>
                </ul>
                <!-- <div class="cart_delivery">
                    <div>
                        <p class="cart_delivery_title">Delivery:</p>
                        <p>UK Standard Delivery</p>
                    </div>
                    <div class="cart_delivery_price">
                        <span :class="{ old_price: isFreeDelivery || freeShipping}">&pound;{{ delivery.toFixed(2) }}</span>
                        <span v-show="isFreeDelivery || freeShipping" class="free">FREE</span>
                    </div>
                </div>
                <div v-if="ff()" class="cart_delivery">
                    <div>
                        <p class="cart_delivery_title"></p>
                        <p>VAT</p>
                    </div>
                    <div class="cart_delivery_price">
                        <span>&pound;{{ (calculateTotal()/6).toFixed(2) }}</span>
                    </div>
                </div> -->
            </div>
            <div class="cart_footer">
                <div class="cart_subtotal">
                    <p>Total: &pound;{{ isFreeDelivery ? calculateSubtotal() : calculateTotal() }}</p>
                </div>
                <button @click="redirectToCart()" class="cart_footer_button">CHECKOUT</button>
            </div>
        </div>
        <div class="cart_wrap dropdown_container" v-show="!items.length">
            <div class="cart_header">
                <span class="cart_header_title">Shopping List is empty</span>
                <span class="cart_header_close js-dropdown-close">close</span>
            </div>
        </div>
    </div>
</template>

<script>
    import { EventBus } from './../event-bus.js';
    import {calculateSubtotalMixin, getCartMixin, calculateTotalMixin} from './../mixins/cartMixins.js';
    import {getDeliveryCost} from './../mixins/cartHelperMixins'
    import {usePromoCodeMixin} from './../mixins/promoCodeMixins.js';
    import {isFF} from "../mixins/helpers";

    export default {
        name: 'shopping-cart-panel',
        mixins: [
            calculateSubtotalMixin,
            getCartMixin,
            usePromoCodeMixin,
            calculateTotalMixin,
            usePromoCodeMixin,
            getDeliveryCost,
            isFF
        ],
        data: () => ({
            items: '',
            subtotal: '',
            sizes: [],
        }),
        computed: {
            getCartCount() {
                return this.items.reduce((count, { quantity }, index, array) => {
                    return (array[index].type === 'cart_item') ? count += quantity : count;
                }, 0)
            }
        },
        watch: {
            items: function () {
                this.setSelectedData();
            }
        },
        beforeMount() {
            this.getCart()
        },
        mounted() {
            EventBus.$on('cart-changed', (items) => {
                this.items = items;
            });
        },
        methods: {
            redirectToCart() {
                if (this.ff()) {
                    window.location.href = '/festive-floristry/cart';
                } else {
                    window.location.href = '/cart';
                }
            },
            setSelectedData() {
                this.sizes = JSON.parse(localStorage.getItem('sizes'));
            }
        },
    }
</script>
