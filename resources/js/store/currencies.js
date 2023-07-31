const storageCurrency = localStorage.getItem('currentCurrency');

let defaultCurrency = storageCurrency ? JSON.parse(storageCurrency) : {
    name: 'gbp',
    sign: '&pound;'
}

const state = () => ({
    currentCurrency: defaultCurrency,
    currencies: [
        {
            name: 'gbp',
            sign: '&pound;'
        },
        {
            name: 'eur',
            sign: '&euro;'
        }
    ],
})

const mutations = {
    SET_CURRENT_CURRENCY: (state, currency) => state.currentCurrency = currency
}

const actions = {
    setCurrentCurrency({commit}, currency) {
        commit('SET_CURRENT_CURRENCY', currency)
        localStorage.setItem('currentCurrency', JSON.stringify(currency))
    }
}

export default {
    state,
    mutations,
    actions
}
