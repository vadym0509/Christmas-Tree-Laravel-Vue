<template>
    <div class="login_content">

        <div v-if="user.email" class="registered">
            <div class="greeting">Hello, <strong>{{user.email}}</strong>!</div>
            <a href="/festive-floristry/logout" class="cta default_button">LOGOUT</a>
        </div>

        <div v-else>
            <form method="POST" action="/festive-floristry/login" class="login_form-auth" @submit="loginSubmit">
                <h3 class="login_form_heading">Login</h3>
                <input type="email" name="email" placeholder="Email address" class="login_input" v-model="login.email">
                <input type="password" name="password" placeholder="Password" class="login_input" v-model="login.password">
                <div class="submit_container">
                    <input type="submit" value="LOGIN" class="cta">
                    <a href="/festive-floristry/forgot-password" class="login_forgot">Forgot your password?</a>
                </div>
            </form>

            <form method="POST" action="/festive-floristry/register" class="login_form-registration" @submit="registerSubmit">
                <h3 class="register_heading">Register as a new user</h3>
                <input type="email" name="email" placeholder="Email address" class="login_input" v-model="register.email">
                <input type="password" name="password" placeholder="Password" class="login_input" v-model="register.password">
                <input type="text" name="company_name" placeholder="Company Name" class="login_input" v-model="register.company_name">
                <input type="text" name="first_name" placeholder="First Name" class="login_input" v-model="register.first_name">
                <input type="text" name="last_name" placeholder="Surname" class="login_input" v-model="register.last_name">
                <div class="terms-container">
                    <input type="checkbox" id="terms" class="styled-checkbox" value="0" @click="register.termsAgree = !register.termsAgree">
                    <label for="terms">Agree to our Terms & Conditions and Privacy Policy</label>
                </div>
                <div class="submit_container">
                    <input type="submit" value="REGISTER" class="cta" :disabled="register.termsAgree == 0">
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import VueToastr from "vue-toastr";

    export default {
        name: 'LoginPanel',
        components: {
            VueToastr
        },
        data: () => ({
            login: {
                email: '',
                password: ''
            },
            register: {
                email: '',
                password: '',
                company_name: '',
                first_name: '',
                last_name: '',
                termsAgree: false
            },
            errors: {},
        }),
        props: ['user'],
        watch: {
            errors: function () {
                for (let key in this.errors) {
                    this.$toastr.e({
                        title: "Error!",
                        msg: this.errors[key][0],
                        position: "toast-top-center",
                    });
                }
            }
        },
        methods: {
            loginSubmit(e) {
                e.preventDefault();
                this.errors = {};
                const {login} = this;
                const formData = new FormData();
                formData.append('email', login.email);
                formData.append('password', login.password);
                axios.post('/festive-floristry/login', formData).then((res) => {
                    this.$toastr.s({
                        title: 'Welcome back!',
                        msg: 'Login successful',
                        position: "toast-top-center",
                    });
                    window.location = res.request.responseURL;
                }).catch((err) => {
                    if (err.response.status === 422){
                        this.errors = err.response.data.errors;
                    }
                })
            },

            registerSubmit(e) {
                e.preventDefault();
                this.errors = {};
                const { register } = this;
                const formData = new FormData();
                formData.append('email', register.email);
                formData.append('password', register.password);
                formData.append('company_name', register.company_name);
                formData.append('first_name', register.first_name);
                formData.append('last_name', register.last_name);
                axios.post('/festive-floristry/register', formData).then((res) => {
                    this.$toastr.s({
                        title: 'Welcome!',
                        msg: 'Register successful',
                        position: "toast-top-center",
                    });
                    window.location = res.request.responseURL;
                }).catch((err) => {
                    if (err.response.status === 422){
                        this.errors = err.response.data.errors;
                    }
                })
            }
        }
    }
</script>

<style lang="scss" scoped>
    .login_content {
        position: absolute;
        right: 0;
        top: 100%;
        flex-direction: column;
        width: 500px;
        background-color: #edece8;
        padding: 15px;
        border: 20px solid #1b353d;
        z-index: 2;

        @media screen and (max-width: 767px) {
            width: 290px;
        }
    }

    .login_form_heading {
        border: none;
        margin-bottom: 10px;
    }

    .login_forgot {
        font-size: 14px;
    }

    .submit_container {
        width: 150px;
        margin-left: auto;
    }

    .register_heading {
        color: #1b353d;
        font-size: 14px;
        margin: 18px 0;
    }

    .login_form-registration {
        border-top: 1px solid black;
        margin-top: 25px;
    }

    .cta {
        margin-top: 60px;
    }

    .terms {
        -webkit-appearance: none;
        border: 1px solid #edece7;
        width: 25px;
        min-width: 25px;
        height: 25px;
        background: rgb(255, 255, 255);
        outline: none;
    }

    .terms-label {
        font-size: 14px;
        margin: 0 0 0 15px;
        font-weight: bold;
    }

    .terms-container {
        display: flex;
        align-items: center;
        line-height: 18px;
    }

    //Checkbox
    .styled-checkbox {
        position: absolute; // take it out of document flow
        opacity: 0; // hide it

        & + label {
            position: relative;
            cursor: pointer;
            padding: 0;
        }

        // Box.
        & + label:before {
            content: '';
            margin-right: 10px;
            display: inline-block;
            vertical-align: text-top;
            width: 20px;
            height: 20px;
            background: white;
        }

        // Box hover
        &:hover + label:before {
            background: #f3ca94;
        }

        // Box focus
        &:focus + label:before {
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.12);
        }

        // Box checked
        &:checked + label:before {
            background: #f3ca94;
        }

        // Disabled state label.
        &:disabled + label {
            color: #b8b8b8;
            cursor: auto;
        }

        // Disabled box.
        &:disabled + label:before {
            box-shadow: none;
            background: #ddd;
        }

        // Checkmark.
        &:checked + label:after {
            content: '';
            position: absolute;
            left: 5px;
            top: 9px;
            background: white;
            width: 2px;
            height: 2px;
            box-shadow:
                2px 0 0 white,
                4px 0 0 white,
                4px -2px 0 white,
                4px -4px 0 white,
                4px -6px 0 white,
                4px -8px 0 white;
            transform: rotate(45deg);
        }
    }
</style>
