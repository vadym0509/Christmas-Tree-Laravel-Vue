import axios from 'axios'
import toastr from 'toastr'

export default {
    get() {
        return axios.get('/api/user/cart')
    },
    post(id, quantity) {
        return axios.post('/api/user/cart/items', {
            id: id,
            quantity: quantity
        }).catch(() => {
            toastr.options.progressBar = true;
            toastr.options.positionClass = 'toast-top-center';
            toastr.warning('Sorry we can’t perform this action');
        })
    },
    postCustomItem(data) {
        return axios.post('/api/user/cart/customItems', data).catch((error) => {
            toastr.options.progressBar = true;
            toastr.options.positionClass = 'toast-top-center';
            toastr.warning(error.response.data.message);
        })
    },
    put(id, quantity) {
        return axios.put(`/api/user/cart/items/${id}`, {
            id: id,
            quantity: quantity
        })
    },
    delete(id) {
        return axios.delete(`/api/user/cart/items/${id}`)
    },
    destroyCart() {
        return axios.delete(`/api/user/cart`)
    },
}
