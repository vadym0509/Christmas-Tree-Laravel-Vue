<template>
    <div class="dropdown_wrap js-dropdown-currency currency">
        <div class="js-dropdown-title currency_title">
            <span class="js-dropdown-title-text">{{currentCurrency.name}}</span>
            <i class="fas fa-chevron-down dropdown_icon"></i>
        </div>
        <div class="dropdown_container currency_container">
            <p class="currency_item js-dropdown-item" v-for="currency in currencies" @click="currencySelect(currency)">
                {{currency.name}}
            </p>
        </div>
    </div>
</template>

<script>
    export default {
        name: "currency-select",
        computed: {
            currentCurrency: {
                get() {
                    return this.$store.state.currencies.currentCurrency
                },
                set(value) {
                    this.$store.dispatch('setCurrentCurrency', value)
                }
            },
            currencies() {
                return this.$store.state.currencies.currencies
            }
        },
        methods: {
            currencySelect(value) {
                this.currentCurrency = value
            }
        }
    }
</script>

<style scoped>

</style>
