<template>
    <li class="cart_product">
        <h3 class="cart_product_name">{{ item.name }}</h3>
        <div :class="{ cart_product_body: true, calculator: true, busy: isBusy }">
            <a href="#" class="cart_item_img">
                <img
                    :src="getMainImageUrl(fullProduct.product.relationships.main_image)"
                    alt="product">
            </a>
            <div class="info_panel_wrap">
                <div class="cart_product_description">
                    <p>{{ item.description }}</p>
                </div>
                <div class="info_panel">
                    <div class="options">
                        <dropdown-product-variation
                            v-for="variationOption in fullProduct.variationOptions"
                            :key="variationOption.id"
                            :options="variationOption"
                            :defaultOptions="fullProduct.defaultOptions"
                            @selectedOption="changeProduct"
                            @selected-product="setSelected"
                        >
                        </dropdown-product-variation>
                    </div>

                    <div v-if="ff() && !item.delivery_cost"  class="quantity">
                        <h3 class="cart_line_title">Quantity</h3>
                        <div class="quantity__value">
                            <button v-if="!ff()"
                                class="text_button symbol"
                                :disabled="isBusy"
                                @click="quantityMinus">-
                            </button>
                            <number-input
                                v-if="ff()"
                                @change="onChange"
                                :max="99999"
                                :min="0"
                                v-model="item.quantity"
                                inline class="quantity_input"
                            ></number-input>
                            <p v-else class="number">{{ item.quantity }}</p>
                            <button v-if="!ff()"
                                class="text_button symbol"
                                :disabled="isBusy"
                                @click="quantityPlus">+
                            </button>
                        </div>
                    </div>
                    <div class="spinner d-flex justify-content-center align-self-center" :style="{visibility: (isBusy) ? 'visible' : 'hidden'}">
                        <div class="spinner-border spinner-border-sm text-black-50"></div>
                    </div>
                    <div class="price">
                        <h3 class="price__value">{{ this.item.meta.display_price.with_tax.value.formatted }}</h3>
                    </div>
                </div>
            </div>
            <i
                class="fas fa-times cart_item_delete"
                @click="removeCartItem(item.product_id)"></i>
        </div>
    </li>
</template>

<script>
    import {EventBus} from './../event-bus.js';
    import cartServices from './../services/cartServices.js';
    import {updateCartItemMixin} from './../mixins/cartMixins.js';
    import {quantityCalcMixin, removeCartItemMixin, getFullProduct, getMainImage} from './../mixins/cartHelperMixins'
    import {changeDefaultProduct} from './../mixins/productMixins.js';
    import {isFF} from './../mixins/helpers.js';

    export default {
        name: 'shopping-cart-tile',
        mixins: [
            updateCartItemMixin,
            quantityCalcMixin,
            isFF,
            removeCartItemMixin,
            getFullProduct,
            changeDefaultProduct,
            getMainImage
        ],
        props: {
            item: {
                type: Object,
                default: () => {
                }
            }
        },
        data: () => ({
            quantity: '',
            defaultProduct: '',
            size: '',
        }),
        methods: {
            storeSelected(size, oldProductId, productId) {
                this.size = size;

                this.$emit('selectedSize', {
                    size,
                    oldProductId,
                    productId,
                });
            },
            setSelected(size) {
                return this.size = size;
            },
            onChange (event){
                this.quantity=event;
                this.updateCartItem();
            },
            changeProduct(data) {
                let oldItemId = this.item.product_id;

                //set checked option
                for (let key in this.fullProduct.defaultOptions) {
                    if (this.fullProduct.defaultOptions[key].name === data.name) {
                        this.fullProduct.defaultOptions[key].id = data.property.id;
                    }
                }

                //get path for child product
                let path = [];
                for (let key in this.fullProduct.defaultOptions) {
                    path.push(this.fullProduct.defaultOptions[key].id);
                }

                function getValueByPath(obj, path) {
                    for (let key in obj) {
                        if (path.includes(key) && obj[key] && typeof obj[key] !== "object") {
                            return obj[key];
                        }
                        if (path.includes(key) === true) {
                            return getValueByPath(obj[key], path);
                        }
                    }
                }
                let childProductKey = getValueByPath(this.fullProduct.product.meta.variation_matrix, path);

                if (oldItemId !== childProductKey) {
                    this.quantity = this.item.quantity;
                    this.addToCartWithoutUpdate(childProductKey, oldItemId);

                    if(data.name === 'Size') {
                        this.storeSelected(data.property.name, oldItemId, childProductKey);
                    }
                }
            },
            async addToCartWithoutUpdate(itemId, oldItemId) {
                const {data} = await cartServices.post(itemId, this.quantity);

                EventBus.$emit('cart-item-changed', {
                    newItems: data,
                    oldItemId: oldItemId,
                    newItemId: itemId
                });

                let productStorage = {
                    product: this.fullProduct.product,
                    variationOptions: this.fullProduct.variationOptions,
                    defaultOptions: this.fullProduct.defaultOptions
                };
                localStorage.setItem(itemId, JSON.stringify(productStorage));
            },
        },
        mounted() {
            this.storeSelected(this.size, null, this.item.product_id)
        },
        watch: {
            item: function () {
                this.getFullProduct();
            }
        }
    }
</script>
