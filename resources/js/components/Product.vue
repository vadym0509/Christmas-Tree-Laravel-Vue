<template>
    <div class="row">
        <div class="col-lg-7 product__images">
            <lingallery :width="600" :height="400" :items="images"/>
        </div>
        <div class="col-lg-5 product__text">
            <div
                class="product__category"
                v-if="product.relationships.categories">
                <h3 v-for="(category, index) in product.relationships.categories" :key="index">
                    <a :href="'/category/' + category.slug">{{ category.name }}</a><span>,</span>
                </h3>
            </div>
            <h2 class="product__title">{{ defaultProduct.name }}</h2>
            <p class="product__meta">{{ defaultProduct.sub_description }}<br />Product code: {{ defaultProduct.sku }}</p>
            <p class="product__description">{{ defaultProduct.description }}</p>
            <div class="product__calculator calculator">
                <div v-if="variationOptions" class="options">
                    <dropdown-product-variation
                        @selectedOption = "changeDefaultProduct"
                        v-for="variationOption in variationOptions"
                        :options="variationOption"
                        :key="variationOption.id"
                        :defaultOptions="defaultOptions"
                    >
                    </dropdown-product-variation>
                </div>
                <div v-if="ff() && !deliveryCostByField" class="quantity">
                    <h3 class="block-title">Quantity</h3>
                    <div class="quantity__value">
                        <button v-if="!ff()" class="text_button symbol" :disabled="isBusy" @click="reduceQuantity">-</button>
                        <number-input v-if="ff()" :max="99999" :min="0" v-model="quantity" inline class="quantity_input"></number-input>
                        <p v-else class="number">{{ quantity }}</p>
                        <button v-if="!ff()" class="text_button symbol" :disabled="isBusy" @click="increaseQuantity">+</button>
                    </div>
                </div>
                <div class="price">
                    <h3 class="block-title">Price</h3>
                    <h3 class="price__value"><span v-html="currentCurrency.sign"></span>{{ (defaultProduct.price[0].amount / 100).toFixed(2) }}</h3>
                </div>
            </div>
            <button :disabled='!isInStock || isBusy' class="cta" type="button" @click="addToCart(defaultProduct.id)">
                <span
                    class="spinner-border spinner-border-sm"
                    role="status"
                    aria-hidden="true"
                    :style="{visibility: (isBusy) ? 'visible' : 'hidden'}"
                ></span>
                {{ ctaLabel }}
            </button>
            <div class="product__shipping">
                <h3 class="block-title" v-on:click="shippingShow = !shippingShow">Shipping & Returns {{shippingShow ? '-' : '+'}}</h3>
                <transition name="fade">
                    <p v-if="shippingShow" class="product__meta" v-html="shippingContent"></p>
                </transition>
            </div>
        </div>
    </div>
</template>

<script>
    import {changeDefaultProduct, addToCart} from './../mixins/productMixins.js';
    import {isFF} from './../mixins/helpers.js';
    import VueNumberInput from '@chenfengyuan/vue-number-input';
    import {getProductItem} from './../mixins/productHelperMixins';
    Vue.use(VueNumberInput);

    export default {
        name: 'product',
        mixins: [changeDefaultProduct, addToCart, isFF, getProductItem],
        props: {
            product: {
                type: Object,
                default: () => {}
            },
            shippingContent: {
                type: String,
            }
        },
        data: () => ({
            defaultProduct: {},
            defaultOptions: [],
            tempOptions: [],
            variationOptions: null,
            quantity: 0,
            images: [],
            shippingShow: false,
            deliveryCostByField: 0,
        }),
        beforeMount() {
            this.getDefaultProduct();
            this.setDefaultOptions();
            this.getOptionsWithKeys();
        },
        mounted() {
            this.getVariations();
            this.quantity = +this.isInStock;
            this.getProduct(this.defaultProduct.id).then(result => this.deliveryCostByField = result.delivery_cost);
        },
        created() {
            this.parseImages();
        },
        computed: {
            currentCurrency() {
                return this.$store.state.currencies.currentCurrency;
            },
            isInStock() {
                return (this.product.meta.stock.available - this.quantity) > 0;
            },
            ctaLabel() {
                return this.isInStock ? 'ADD TO CART' : 'OUT OF STOCK';
            }
        },
        methods: {
            setDefaultOptions () {
                //get path to product ID
                function findPath(obj, item) {
                    for(let key in obj) {
                        if(obj[key] && typeof obj[key] === "object") {
                            let result = findPath(obj[key], item);
                            if(result) {
                                result.unshift(key);
                                return result;
                            }
                        } else if(typeof obj[key] === "string") {
                            return [key];
                        }
                    }
                }

                this.tempOptions = findPath(this.product.meta.variation_matrix, this.defaultProduct.id);
            },
            getOptionsWithKeys() {
                let defaultOptions = [];
                for (let keyVariations in this.product.meta.variations) {
                    for (let keyOptions in this.product.meta.variations[keyVariations].options) {
                        if(this.tempOptions && this.tempOptions.includes(this.product.meta.variations[keyVariations].options[keyOptions].id)) {
                            let itemId = this.product.meta.variations[keyVariations].options[keyOptions].id;
                            let itemName = this.product.meta.variations[keyVariations].name
                            defaultOptions.push({name : itemName, id : itemId});
                        }
                    }
                }
                this.defaultOptions = defaultOptions;
            },
            getDefaultProduct() {
                return this.defaultProduct = this.product.relationships.children ? this.product.relationships.children[0] : this.product;
            },
            parseImages() {
                let images = [];

                this.product.relationships.files.forEach(function(item) {
                    images.push({
                        src: item.link.href,
                        thumbnail: item.link.href
                    });
                });
                this.images = images;
            },
            getVariations(){
                if (this.product.meta.variations) {
                    this.variationOptions = this.product.meta.variations;
                }
            },
            reduceQuantity() {
                if (this.quantity > 1) {
                    this.quantity--;
                }
            },
            increaseQuantity() {
                if (!this.isInStock) return;
                this.quantity++;
            },
        }
    }
</script>

