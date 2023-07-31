<template>
    <div class="login_button_container" :class="isPanelShowing ? 'selected' : ''" @click.stop >
        <div class="nav_bar_right_user" @click="togglePanel">
            <a href="#">
                <i class="fas fa-user"></i>
            </a>
        </div>
        <login-panel :user="user" v-if="isPanelShowing"/>
    </div>
</template>

<script>
    export default {
        name: 'LoginButton',
        data: () => ({
            isPanelShowing: false,
            user: ''
        }),
        methods: {
            togglePanel(e) {
                e.stopPropagation()
                this.isPanelShowing = !this.isPanelShowing
            }
        },
        mounted() {
            document.body.addEventListener('click', () => {
                this.isPanelShowing = false
            });

            axios.post('/api/user/name').then(response => {
                this.user = response.data
            })
        }
    }
</script>

<style lang="scss">
    .login_button_container {
        height: 100%;
        position: relative;
        align-items: center;
        display: flex;

        &.selected {
            background-color: #1b353d;
        }
    }

    .header .nav_bar_right_user {
        height: 100%;
        display: flex;
        align-items: center;
    }
</style>
