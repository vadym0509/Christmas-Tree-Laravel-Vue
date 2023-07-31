import { EventBus } from './../event-bus.js';
import cartServices from './../services/cartServices.js';

export const getCartMixin = {
    methods: {
        async getCart() {
            const {data} = await cartServices.get();
            this.items = data;
            this.dataLoaded = true;
        }
    }
};

export const updateCartItemMixin = {
    data() {
        return {
            isBusy: false,
        }
    },
    methods: {
        async updateCartItem() {
            if(this.isBusy) return false; //check if previous request was done

            this.isBusy = true;
            const {data} = await cartServices.put(this.item.id, this.quantity);
            EventBus.$emit('cart-changed', data);
            this.isBusy = false;
        }
    }
};

export const destroyCartItemMixin = {
    methods: {
        async destroyCartItem() {
            const {data} = await cartServices.delete(this.item.id);
            EventBus.$emit('cart-changed', data)
        }
    }
};

export const calculateSubtotalMixin = {
    data: () => ({
        subtotal: 0,
    }),
    methods: {
        calculateSubtotal() {
            let subtotal = 0
            for (let key in this.items) {
                if(this.items[key].free_shipping === true){
                    continue;
                }
                subtotal += +this.items[key].meta.display_price.with_tax.value.amount
            }
            return this.subtotal = +(subtotal / 100).toFixed(2);
        }
    }
};

export const calculateTotalMixin = {
    methods: {
        calculateTotal() {
            if(this.freeShipping || this.isFreeDelivery){
                return this.ff()?(this.calculateSubtotal()*1.2).toFixed(2):(this.calculateSubtotal()).toFixed(2);
            }else{
                return this.ff()?((this.calculateSubtotal() + this.delivery)*1.2).toFixed(2):(this.calculateSubtotal() + this.delivery).toFixed(2);
            }
        }
    }
};

export const calculateQuantityMixin = {
    methods: {
         reduceQuantity() {
            this.quantity = this.item.quantity;
            if (this.quantity > 0) {
                this.updateCartItem()
            } else {
                this.destroyCartItem()
            }
        },
        increaseQuantity() {
            this.quantity = this.item.quantity;
            this.updateCartItem()
        },
    }
};

