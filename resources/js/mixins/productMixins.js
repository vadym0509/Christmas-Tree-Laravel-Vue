import { EventBus } from './../event-bus.js';
import cartServices from './../services/cartServices.js';
import {getProductItem} from './productHelperMixins';

export const changeDefaultProduct = {
    mixins: [getProductItem],
    methods: {
         async changeDefaultProduct(data) {
            for (let key in this.defaultOptions) {
                if (this.defaultOptions[key].name === data.name) {
                    this.defaultOptions[key].id = data.property.id;
                }
            }

            let path = [];
            for (let key in this.defaultOptions) {
                path.push(this.defaultOptions[key].id);
            }

            function getValueByPath(obj, path) {
                for (let key in obj) {
                    if(path.includes(key) && obj[key] && typeof obj[key] !== "object") {
                        return obj[key];
                    }
                    if (path.includes(key) === true) {
                        return getValueByPath(obj[key], path);
                    }
                }
            }

            let childProductKey = getValueByPath(this.product.meta.variation_matrix, path);
            for(let key in this.product.relationships.children) {
                if(this.product.relationships.children[key].id === childProductKey) {

                    const product = await this.getProduct(this.product.relationships.children[key].id);
                    this.deliveryCostByField = parseInt(product.delivery_cost);

                    this.defaultProduct = this.product.relationships.children[key];
                }
            }
        }
    }
};

export const addToCart = {
    data() {
        return {
            isBusy: false,
        }
    },
    methods: {
        async addToCart(itemId) {
            if(this.isBusy) return false;

            this.isBusy = true;
            const {data} = await cartServices.post(itemId, this.quantity);
            EventBus.$emit('cart-changed', data);
            let productStorage = {
                product: this.product,
                variationOptions: this.variationOptions,
                defaultOptions: this.defaultOptions
            };
            localStorage.setItem(this.defaultProduct.id, JSON.stringify(productStorage));

            this.isBusy = false;
        },
    }
};

export const addCustomItemToCart = {
    methods: {
        addCustomItemToCart(data) {
            return cartServices.postCustomItem(data).then((response) => {
                EventBus.$emit('cart-changed', response.data);
            });
        },
    }
};
