<template>
    <a @click="clearCartSate" :href="getUrl()" class="nav_bar_right_button">
        <span v-if="ff()" class="ff-theme">kilted christmas</span>
        <span v-else>Trade Login</span>
        <i class="fas fa-caret-right"></i>
    </a>
</template>

<script>
    import {getCartMixin} from './../mixins/cartMixins.js';
    import {EventBus} from './../event-bus.js';
    import cartServices from './../services/cartServices.js';
    import {isFF} from './../mixins/helpers.js';
    export default {
        name: "NavRightButton",
        mixins:[getCartMixin, isFF],
        data: () => ({
            items: ''
        }),
        methods: {
            async clearCartSate() {
                for (let item of this.items) {
                    const {data} = await cartServices.delete(item.id)
                    EventBus.$emit('cart-changed', data)
                }
            },
            getUrl() {
                return this.ff() ? '/' : '/festive-floristry/login';
            }
        },
        created() {
            this.getCart()
        },
        mounted() {
            EventBus.$on('cart-changed', (items) => {
                this.items = items
            });
        }
    }
</script>
