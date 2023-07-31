import {getProductById} from "../services/productServices";

export const getProductItem = {
    methods: {
        async getProduct(id) {
            return await getProductById(id)
        }
    }
};
