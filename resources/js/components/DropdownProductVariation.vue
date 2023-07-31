<template>
    <div class="size" v-if="options">
        <h3 class="block-title cart_line_title">{{ options.name }}</h3>
        <div class="product_select js-dropdown dropdown_wrap" :class="{'show': showDropdown}">
            <h4 class="product_select-title" @click="showDropdown = !showDropdown">
                <span>{{ defaultOptionChecked ? defaultOptionChecked.name : options.options['0'].name }}</span>
                <i class="fas fa-chevron-down dropdown_icon"></i>
            </h4>
            <div class="dropdown_container product_container">
                <p class="product_item"
                   v-for="(option, index) in options.options"
                   :key="index"
                   @click="check(option)">
                    {{ option.name }}
                </p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'dropdown-product-variation',
        props: {
            options: {
                type: [Object, Array, String],
                default: null
            },
            defaultOptions: {
                type: Array,
                default: null
            }
        },
        data: () => ({
            defaultOptionChecked: '',
            showDropdown: false
        }),
        mounted() {
            this.restoreSelectedVariation();
            this.setOption();
            this.sendSelectedOptions();
        },
        methods: {
            check (option) {
                this.defaultOptionChecked = option;
                this.$emit('selectedOption', {
                    property: this.defaultOptionChecked,
                    name: this.options.name
                });

                this.sendSelectedOptions();
                this.showDropdown = false;
            },
            sendSelectedOptions() {
                if(this.options.name === 'Size') {
                    this.$emit('selected-product', this.defaultOptionChecked.name);
                }
            },
            setOption() {
                for (let key in this.defaultOptions) {
                    if(this.defaultOptions[key].name === this.options.name) {
                        let optionId = this.defaultOptions[key].id;
                        for (let key in this.options.options) {
                            if(optionId === this.options.options[key].id) {
                                return this.defaultOptionChecked = this.options.options[key];
                            }
                        }
                    }
                }
            },
            restoreSelectedVariation() {
                if (localStorage['defaultOptionChecked']) {
                    this.defaultOptionChecked = JSON.parse(localStorage.getItem('defaultOptionChecked'));
                }
            }
        }
    }
</script>
