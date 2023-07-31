<template>
    <div class="steps-form">
        <div v-if="!ff()" class="steps-form__head">
            <div :class="{'active': currentStep === 1}" class="steps-form__head-item">
                1. Delivery
            </div>
            <div :class="{'active': currentStep === 2}" class="steps-form__head-item">
                2. Payment & Confirm
            </div>
        </div>
        <div class="steps-form__body">
            <div
                v-if="currentStep === 1"
                class="step-one"
            >
                <div class="steps-form__body-title">
                    Delivery details
                </div>
                <form>
                    <div ref="form" class="row">
                        <div class="col-sm-12 col-md-6">
                            <input
                                v-model="formShipping.firstName"
                                class="input-default mb-4 required"
                                type="text"
                                placeholder="First Name"
                                required="true"
                                :class="{invalid: !formShipping.firstName && requiredField}"
                            >
                        </div>
                        <div class="col-sm-12 col-md-6 mb-4">
                            <input
                                v-model="formShipping.lastName"
                                class="input-default required"
                                type="text"
                                placeholder="Last Name"
                                required="true"
                                :class="{invalid: !formShipping.lastName && requiredField}"
                            >
                        </div>
                        <div v-if="ff()" class="col-12 mb-4">
                            <input
                                v-model="formShipping.company_name"
                                class="input-default required"
                                type="text"
                                v-if="ff()"
                                placeholder="Company Name"
                                :class="{invalid: !formShipping.company_name && requiredField}"
                            >
                        </div>
                        <div class="col-sm-12 col-md-6 mb-4">
                            <input
                                v-model="formShipping.email"
                                class="input-default required"
                                name="email"
                                type="email"
                                placeholder="Email"
                                :class="{invalid: !formShipping.email && requiredField || errors.email}"
                            />
                        </div>
                        <div class="col-sm-12 col-md-6 mb-4">
                            <cleave
                                v-model="formShipping.phone"
                                class="input-default required"
                                :class="{invalid: !formShipping.phone && requiredField || errors.phone}"
                                name="phone-number"
                                :options="options.phone"
                                placeholder="Phone number"
                                required="true"
                            />
                        </div>
                        <div class="col-12 mb-4">
                            <input
                                v-model="formShipping.address1"
                                class="input-default required"
                                type="text"
                                placeholder="Address line 1"
                                required="true"
                                :class="{invalid: !formShipping.address1 && requiredField}"
                            >
                        </div>
                        <div class="col-12 mb-4">
                            <input
                                v-model="formShipping.address2"
                                class="input-default"
                                type="text"
                                placeholder="Address line 2"
                            >
                        </div>
                        <div class="col-12 col-md-6 mb-4">
                            <input
                                v-model="formShipping.city"
                                class="input-default required"
                                type="text"
                                placeholder="City"
                                required="true"
                                :class="{invalid: !formShipping.city && requiredField}"
                            >
                        </div>
                        <div class="col-sm-12 col-md-6 mb-4">
                            <input
                                v-model="formShipping.postCode"
                                class="input-default required"
                                name="zip-code"
                                type="text"
                                placeholder="Postcode / Zip code"
                                required="true"
                                :class="{invalid: !formShipping.postCode && requiredField}"
                            />
                        </div>
                        <div class="col-sm-12 col-md-6 mb-4">
                            <v-select
                                :options="addressData.regions"
                                v-model="formShipping.region"
                                placeholder="Country / County"
                                class="style-chooser required"
                                autocomplete="off"
                                :class="{invalid: !formShipping.region && requiredField}"
                            ></v-select>
                        </div>
                        <div class="col-sm-12 col-md-6 mb-4">
                            <v-select
                                :options="getCountriesByRegion"
                                v-model="formShipping.country"
                                placeholder="Region"
                                :disabled="!formShipping.region"
                                label="County"
                                @input="setSelectedCountry"
                                class="required style-chooser"
                                autocomplete="off"
                                :class="{invalid: !formShipping.country && requiredField}"
                            ></v-select>
                        </div>
                    </div>
                </form>
                <button class="cta" :class="{'disable': isNextStepDisable}" @click.prevent="addStep" :disabled="busy">Next</button>
            </div>
            <div
                v-show="currentStep === 2"
                class="step-two"
            >
                <div class="steps-form__body-title">
                    Payment details
                </div>
                <div class="steps-form-payment">
                    <div class="steps-form-payment__head">
                        <div class="steps-form-payment__title">
                            Credit & Debit Card
                        </div>
                        <div class="steps-form-payment__images">
                            <img src="/assets/frontend/images/mastercard.png" alt="Mastercard">
                            <img src="/assets/frontend/images/visa.png" alt="Visa">
                            <img src="/assets/frontend/images/paypal.png" alt="PayPal">
                            <img src="/assets/frontend/images/sage_pay.svg" alt="SagePay">
                        </div>
                    </div>
                    <input
                        v-model="card.cardHolderName"
                        class="input-default mb-4"
                        type="text"
                        placeholder="Cardholder Name"
                    />
                    <div class="credit-card">
                        <cleave
                            v-model="card.number"
                            :options="options.number"
                            class="credit-card__number"
                            name="card-number"
                            placeholder="Card number"
                        />
                        <cleave
                            v-model="card.date"
                            :options="options.date"
                            class="credit-card__date"
                            name="card-date"
                            placeholder="MM / YY"
                        />
                        <cleave
                            v-model="card.cvc"
                            :options="options.cvc"
                            class="credit-card__cvc"
                            name="card-cvc"
                            placeholder="CVC"
                        />
                    </div>
                    <button class="cta mt-4" :class="{'disable': isOrderDisable}" @click="order" :disabled="busy">Place Order</button>
                    <span v-if="paymentFailed" class="text-danger">Your payment could not be processed right now. Please refresh the page to try again.</span>

                    <form id="pa-form" ref="secureForm" method="post" :action="acs.url">
                        <input type="hidden" name="PaReq" :value="acs.PaReq">
                        <input type="hidden" name="TermUrl" :value="acs.TermUrl">
                        <input type="hidden" name="MD" :value="acs.MD">
                    </form>

                </div>
                <div class="steps-form__body-title d-flex justify-content-between mt-5">
                    Your Order Is Going To
                    <span class="pointer" @click="prevStep">Edit</span>
                </div>
                <div class="steps-form-order">
                    <p v-for="value in formShipping">
                        {{value}}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Cleave from 'vue-cleave-component';
    import {sagePayMerchantSessionKeyMixin} from './../mixins/sagePayMixins.js';
    import cartServices from './../services/cartServices.js';
    import {addCustomItemToCart} from './../mixins/productMixins';
    import {checkout} from './../services/checkoutServices.js';
    import {isFF} from './../mixins/helpers.js';
    import vSelect from 'vue-select';
    import jsonData from '../store/regions-countries.json'
    import LoginButton from "./Login/LoginButton";
    import axios from 'axios';


    export default {
        components: {
            Cleave,
            vSelect
        },
        mixins: [sagePayMerchantSessionKeyMixin, isFF, addCustomItemToCart],
        props:['delivery', 'deliveryDate', 'vat'],
        data() {
            return {
                acs: {},
                requiredField: false,
                errors: {},
                regionsCountries: jsonData,
                addressData: {
                    regions: [],
                    countries: []
                },
                busy: false,
                paymentFailed: false,
                currentStep: 1,
                formShipping: {
                    firstName: '',
                    lastName: '',
                    company_name: '',
                    address1: '',
                    address2: '',
                    city: '',
                    postCode: '',
                    region: '',
                    country: '',
                    phone: '',
                    email: '',
                },
                card: {
                    cardholderName: '',
                    number: '',
                    date: '',
                    cvc: '',
                },
                options: {
                    number: {
                        creditCard: true
                    },
                    date: {
                        date: true,
                        datePattern: ['m', 'y'],
                        delimiter: '/',
                    },
                    cvc: {
                        numeral: true,
                        numeralIntegerScale: 3,
                        stripLeadingZeroes: false
                    },
                    phone: {
                        numeral: true,
                        stripLeadingZeroes: false,
                        numeralIntegerScale: 11,
                        delimiter: '',
                    }
                }
            }
        },
        mounted() {
            //get unique Regions
            this.addressData.regions = [... new Set(this.regionsCountries.map(data => data.Region))];

            //get user data
            axios.post('/api/user/name').then(response => {
                if(response.data.id !== undefined){
                    this.formShipping.email=response.data.email;
                    this.formShipping.firstName=response.data.first_name;
                    this.formShipping.lastName=response.data.last_name;
                    this.formShipping.company_name=response.data.company_name;
                }
            })
        },
        computed: {
            isNextStepDisable: function () {
                return Object.keys(this.formShipping).some(key => {
                    if (key === 'address2' || ((!this.ff()) && key === 'company_name')) {
                        return false;
                    }
                    return this.formShipping[key] === '' || this.formShipping[key] === null;
                });
            },
            isOrderDisable: function () {
                return Object.keys(this.card).some(key => {
                    if (key === 'cardholderName') {
                        return false;
                    }

                    return this.card[key] === '';
                });
            },
            getCountriesByRegion: function () {
                this.addressData.countries = this.regionsCountries.filter(data => {
                    return data.Region === this.formShipping.region;
                });
                if (this.addressData.countries[0]) {
                    this.formShipping.country = this.addressData.countries[0].County;
                }
                if(!this.formShipping.region) {
                    this.formShipping.country = '';
                }
                return this.addressData.countries;
            },
        },
        methods: {
            handleErrors() {
                this.errors = {};
                this.phoneValidate();
                this.validEmail();
                if (Object.keys(this.errors).length > 0) {
                    for(let key in this.errors) {
                        this.$toastr.w({
                            title: this.errors[key].title,
                            msg: this.errors[key].message,
                            position: "toast-top-center",
                        });
                    }
                    return false;
                }
                return true;
            },
            setSelectedCountry(val) {
                if (val) {
                    return this.formShipping.country = val.County;
                }
            },
            addStep() {
                this.requiredField = true;

                if (!this.isNextStepDisable && this.handleErrors()) {
                    this.currentStep += 1;
                }
            },
            prevStep() {
                this.currentStep -= 1;
            },
            getCustomerData() {
                return {
                    customer: {
                        name: `${this.formShipping.firstName} ${this.formShipping.lastName}`,
                        email: this.formShipping.email,
                    },
                    billing_address: {
                        first_name: this.formShipping.firstName,
                        last_name: this.formShipping.lastName,
                        company_name: this.formShipping.company_name,
                        line_1: this.formShipping.address1,
                        line_2: this.formShipping.address2,
                        city: this.formShipping.city,
                        postcode: this.formShipping.postCode,
                        county: this.formShipping.region,
                        country: this.formShipping.country,
                    },
                    shipping_address: {
                        first_name: this.formShipping.firstName,
                        last_name: this.formShipping.lastName,
                        company_name: this.formShipping.company_name,
                        line_1: this.formShipping.address1,
                        line_2: this.formShipping.address2,
                        city: this.formShipping.city,
                        postcode: this.formShipping.postCode,
                        county: this.formShipping.region,
                        country: this.formShipping.country,
                        phone_number: this.formShipping.phone,
                        instructions: 'Delivery date: '+this.deliveryDate,
                    },
                };
            },
            async refreshMerchantSessionKey()
            {
                // Set availableSagePayMerchantSessionKey as a new value to get cardIdentifier.
                const {data} = await axios.post('/api/cart/get-merchant-sessionkey');

                return data;
            },
            async order() {
                if(!this.cardValidate()) {
                    console.error('something went wrong');
                    return;
                }

                this.busy = true;
                this.paymentFailed = false;

                const sagePayMerchantSessionKey = await this.refreshMerchantSessionKey();

                sagepayOwnForm({ merchantSessionKey: sagePayMerchantSessionKey })
                    .tokeniseCardDetails({
                        cardDetails: {
                            cardholderName: this.card.cardHolderName,
                            cardNumber: this.card.number,
                            expiryDate: this.card.date,
                            securityCode: this.card.cvc,
                        },
                        onTokenised: async (result) => {
                            if (result.success) {

                                let cardIdentifier = result.cardIdentifier;
                                // Add VAT fee to thecustom cart.
                                if(this.ff()){
                                    await this.addCustomItemToCart({
                                        name: "VAT",
                                        sku: "vat",
                                        description: "VAT fee",
                                        quantity: 1,
                                        price: {
                                            amount: this.vat * 100
                                        }
                                    });
                                }

                                // add delivery cost to cart as new custom product
                                await this.addCustomItemToCart({
                                    name: "Delivery",
                                    sku: "delivery",
                                    description: "Cost of delivery",
                                    quantity: 1,
                                    price: {
                                        amount: this.delivery * 100
                                    }
                                });

                                checkout(sagePayMerchantSessionKey, cardIdentifier, this.getCustomerData(), {isFF: this.ff()})
                                    .then(async (r) => {

                                        if (r.data['3d_secure']) {
                                            const secureData = JSON.parse(r.data.transaction);

                                            this.acs = {
                                                url: secureData.acsUrl,
                                                PaReq: secureData.paReq,
                                                MD: secureData.transactionId,
                                                TermUrl: `${r.data.finish_url}?PaReq=${secureData.paReq}&MD=${secureData.transactionId}`
                                            };

                                            setTimeout(() => {
                                                this.$refs.secureForm.submit();
                                            }, 0);
                                        }

                                        // Destroy our cart before redirecting.
                                        await cartServices.destroyCart();

                                        if (r.data.redirect) {
                                            window.location.href = r.data.redirect;
                                        }
                                    })
                                    .catch((e) => {
                                        // If failed to checkout, destroy the cart.
                                        cartServices.destroyCart();
                                        this.paymentFailed = true;
                                        this.busy = false;
                                    });
                            } else {
                                // Auth fail - customer needs to refresh to get a new merchant session key.
                                cartServices.destroyCart();
                                this.paymentFailed = true;
                                this.busy = false;
                            }
                        }
                    });
            },
            cardValidate() {
                //length of the credit card params
                const validateLengths = {
                    'number': [16,15,14,13],
                    'date': [4],
                    'cvc': [3]
                };
                let countError = 0;
                for(let key in this.card) {
                    if (validateLengths[key] && !validateLengths[key].includes(this.card[key].length)) {
                        countError++;
                        this.$toastr.w({
                            title: 'Incorrect data!',
                            msg: 'Please check ' + key.toUpperCase() + ' field',
                            position: "toast-top-center",
                        });
                    }
                }
                return countError <= 0;
            },

            phoneValidate() {
                if (this.formShipping.phone.length < 9) {
                    this.errors.phone = {
                            title: 'Incorrect phone!',
                            message: 'Length of the phone filed can\'t be less then 9 digits!'
                    };
                }
            },
            validEmail: function () {
                let re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                if (!re.test(this.formShipping.email)) {
                    this.errors.email = {
                        title: 'Incorrect mail!',
                        message: 'Please enter valid value!',
                    };
                }
            }
        }
    }
</script>
<style lang="scss">
    @import "../../../node_modules/vue-select/src/scss/vue-select.scss";
    .v-select {
        border: 1px solid rgba(60, 60, 60, 0.26);
    }
    .style-chooser .vs__search::placeholder,
    .style-chooser .vs__dropdown-toggle,
    .style-chooser .vs__dropdown-menu {
        border: 0;
        border-radius: 0;
        font: 400 14px Arial;
    }
    .style-chooser .vs__search::placeholder {
        color: #757575;
    }
    .style-chooser .vs__dropdown-toggle {
        padding: 5px 0 7px;
    }
    .invalid {
        border-color: red;
    }
</style>
