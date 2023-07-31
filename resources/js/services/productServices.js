import axios from 'axios'

export async function getProductById(id) {
    const {data} = await axios.get(`/api/product/${id}`)
    return data
}
