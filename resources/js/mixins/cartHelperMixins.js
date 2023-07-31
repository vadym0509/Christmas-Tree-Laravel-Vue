import {updateCartItemMixin, destroyCartItemMixin, calculateQuantityMixin} from './cartMixins.js';

export const quantityCalcMixin = {
    mixins: [updateCartItemMixin, calculateQuantityMixin],
    methods: {
        quantityMinus () {
            this.item.quantity --;
            this.reduceQuantity();
        },
        quantityPlus () {
            if (!this.isInStock) return;
            this.item.quantity ++;
            this.increaseQuantity();
        }
    },
    computed: {
        isInStock() {
            return (this.fullProduct.product.meta.stock.available - this.quantity) > 0;
        },
    },
};

export const removeCartItemMixin = {
    mixins: [destroyCartItemMixin],
    data() {
        return {
            showItem: true
        }
    },
    methods: {
        removeCartItem(id) {
            this.showItem = false;
            this.destroyCartItem();
            localStorage.removeItem(id);
        }
    }
};

export const getFullProduct = {
    data: () => ({
        fullProduct: {}
    }),
    beforeMount() {
        this.getFullProduct();
    },
    methods: {
        getFullProduct() {
            let fullProduct = localStorage.getItem(this.item.product_id);
            this.fullProduct = JSON.parse(fullProduct);

            if(this.fullProduct.defaultOptions.length === 0){
                let defaultOptions = [];
                let variationOptions=this.fullProduct.variationOptions;
                for(let key in variationOptions){
                    defaultOptions.push({ name: variationOptions[key].name, id: variationOptions[key].options[0].id });
                }
                this.fullProduct.defaultOptions=defaultOptions;
            }
        }
    }
};

export const getDeliveryCost = {
    data: () => ({
        delivery: 0,
        dpdDeliveryCost: 5,
        isFreeDelivery: false,
        tuffnellsItemDeliveryCost: 15,
    }),
    methods: {
        getDeliveryCost() {
            return this.ff() ? this.ffDeliveryCost() : this.ktDeliveryCost();
        },
        ffDeliveryCost() {
            let othersDeliveryCost = 0;
            let deliveryFieldCost = 0;

            for (let index in this.items) {
                if (this.items[index].type === 'promotion_item') {
                    continue;
                }

                if (this.items[index].delivery_cost) {
                    deliveryFieldCost += this.items[index].delivery_cost;
                } else {
                    let priceByQuantity = this.items[index].meta.display_price.with_tax.value.amount /100;
                    othersDeliveryCost += this.getDeliveryCostByTotalPrice(priceByQuantity);
                }
            }
            this.delivery = deliveryFieldCost + othersDeliveryCost;

        },
        ktDeliveryCost() {
            let useDpd = false;
            let tuffnellsTotalDeliveryCost = 0;

            for (let key in this.items) {
                if (this.items[key].type === 'promotion_item') {
                    continue;
                }

                if (this.items[key].use_tuffnells === true) {
                    tuffnellsTotalDeliveryCost += this.tuffnellsItemDeliveryCost * this.items[key].quantity;
                } else {
                    useDpd = true;
                }
            }

            this.delivery = tuffnellsTotalDeliveryCost + (useDpd ? this.dpdDeliveryCost : 0);

            return this.isFreeDelivery = this.subtotal >= 150;
        },
        getDeliveryCostByTotalPrice(price) {
            const costData = [
                {start: 0, end: 25, cost: 8},
                {start: 25, end: 40, cost: 10},
                {start: 40, end: 70, cost: 12},
                {start: 70, end: 100, cost: 14},
                {start: 100, end: 150, cost: 16},
                {start: 150, end: 200, cost: 18},
                {start: 200, end: 250, cost: 20},
                {start: 250, end: 300, cost: 22},
                {start: 300, end: 350, cost: 24},
                {start: 350, end: 400, cost: 26},
                {start: 400, end: 450, cost: 28},
                {start: 450, end: 500, cost: 30},
                {start: 500, end: 550, cost: 32},
                {start: 550, end: 600, cost: 34},
                {start: 600, end: 650, cost: 36},
                {start: 650, end: 700, cost: 38},
                {start: 700, end: 750, cost: 40},
                {start: 750, end: 800, cost: 42},
                {start: 800, end: 850, cost: 44},
                {start: 850, end: 900, cost: 46},
                {start: 900, end: 950, cost: 48},
                {start: 950, end: 1000, cost: 50},
            ];

            for (let key in costData) {
                if(price === 0) {
                    return this.delivery = 0;
                } else if(_.inRange(price, costData[key].start, costData[key].end)) {
                    return this.delivery = costData[key].cost;
                }
            }
            return this.delivery = 50;
        },
    },
    watch: {
        subtotal: function () {
            this.getDeliveryCost();
        }
    }
};

export const getMainImage = {
    methods: {
        getMainImageUrl(mainImage) {
            return mainImage ? mainImage.link.href : '/assets/frontend/images/no-image.webp';
        }
    }
};
