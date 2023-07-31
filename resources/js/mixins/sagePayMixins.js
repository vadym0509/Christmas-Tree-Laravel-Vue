export const sagePayMerchantSessionKeyMixin = {
    props: {
        sagePayMerchantSessionKey: String,
    },
    data() {
        return {
            availableSagePayMerchantSessionKey: this.sagePayMerchantSessionKey
        }
    }
}
