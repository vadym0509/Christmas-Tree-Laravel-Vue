import axios from 'axios'

export default {
    post(promoCode) {
        return axios.post('/api/promo-code/use', {
            code: promoCode
        }).catch(function (error) {
            console.log(error);
            return error.response.status;
        });
    }
}
