import promoCodeServices from './../services/promoCodeServices';
import {EventBus} from "../event-bus";

export const usePromoCodeMixin = {
    data: () => ({
        promoItem: '',
        promoValue: '£0.00',
        freeShipping: false,
        error: false
    }),
    watch: {
        promoValue : {
            handler: 'getDiscountValue',
            deep: true
        }
    },
    methods: {
        async usePromoCode() {
            const {data} = await promoCodeServices.post(this.promoCode);

            if (data && typeof data === 'object') {
                EventBus.$emit('cart-changed', data)
                this.error = false;
            }
            else EventBus.$emit('cart-error')
        },
        getDiscountItem() {
            this.promoItem = this.items.find(item => item.type === 'promotion_item');
        },
        getDiscountValue() {
            if (this.promoItem) {
                this.freeShipping = this.promoItem.free_shipping;
                if(this.freeShipping){
                    this.promoValue = "-£"+this.delivery.toFixed(2);
                }else{
                    this.promoValue = this.promoItem.meta.display_price.with_tax.value.formatted;
                }
            }
        }
    },
    updated() {
        this.getDiscountItem();
        this.getDiscountValue();
    }
}
