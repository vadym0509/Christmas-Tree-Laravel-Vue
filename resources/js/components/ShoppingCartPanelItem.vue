<template>
    <li v-if="showItem" :class="{ busy: isBusy, cart_item: true }">
        <a href="#" class="cart_item_img">
            <img :src="getMainImageUrl(fullProduct.product.relationships.main_image)" alt="product">
        </a>
        <div class="cart_item_content">
            <div class="cart_item_content_top">
                <p class="cart_item_title">{{ item.name }}</p>
                <p>{{ item.description }}</p>
            </div>
            <div class="cart_item_content_bottom">
                <div v-if="ff() && !item.delivery_cost" class="quantity">
                    <button v-if="!ff()"
                        class="text_button quantity_minus"
                        :disabled="isBusy"
                        @click="quantityMinus">-</button>
                    <number-input v-if="ff()" @change="onChange" :max="99999" :min="0" v-model="item.quantity" inline class="quantity_input"></number-input>
                    <div v-else class="quantity_count">{{ item.quantity }}</div>
                    <button v-if="!ff()"
                        class="text_button quantity_plus"
                        :disabled="isBusy"
                        @click="quantityPlus">+</button>
                </div>
                <div class="spinner d-flex justify-content-center align-self-center" :style="{visibility: (isBusy) ? 'visible' : 'hidden'}">
                    <div class="spinner-border spinner-border-sm text-black-50"></div>
                </div>
                <span class="cart_item_price">{{ this.item.meta.display_price.with_tax.value.formatted }}</span>
            </div>
        </div>
        <i
            class="fas fa-times cart_item_delete"
            @click="removeCartItem"
        ></i>
    </li>
</template>

<script>
    import {updateCartItemMixin, destroyCartItemMixin} from './../mixins/cartMixins.js';
    import {isFF} from './../mixins/helpers.js';
    import {quantityCalcMixin, removeCartItemMixin, getFullProduct, getMainImage} from './../mixins/cartHelperMixins'

    export default {
        name: 'shopping-cart-panel-item',
        mixins: [updateCartItemMixin, quantityCalcMixin, isFF, destroyCartItemMixin, removeCartItemMixin, getFullProduct, getMainImage],
        props: {
            item: {
                type: Object,
                default: () => {}
            }
        },
        data: () => ({
            quantity: '',
        }),
        methods: {
            onChange (event){
                this.quantity=event;
                this.updateCartItem()
            }
        },
    }
</script>
