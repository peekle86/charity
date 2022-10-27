<template>
    <div class="auth">
        <div class="auth-content">
            <div class="container">
                <div class="row get-started-block justify-content-between">
                    <div class="col-md-5">
                        <div class="bs-example">
                            <ul class="nav nav-tabs main-navigation-login col-md-10 mx-auto d-flex justify-content-around">
                                <li class="nav-item text-center list-item-custom-logim-form">
                                    <a class="nav-link--main" data-toggle="tab" href="#login" @click.prevent="changeAuth('In')">
                                        <p class="login-form-button" :class="{ 'login-form-button_active': login }">
                                            Login
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item text-center list-item-custom-logim-form">
                                    <a class="nav-link--main" data-toggle="tab" href="#sign-up" @click.prevent="changeAuth('Up')">
                                        <p class="login-form-button" :class="{ 'login-form-button_active': !login }">
                                            Sign up
                                        </p>
                                    </a>
                                </li>
                            </ul>
                            <b-alert v-model="is_error" variant="danger">{{ error }}</b-alert>
                            <b-alert v-model="is_success" variant="success">{{ error }}</b-alert>
                            <div class="tab-content text-center">
                                <transition name="fade" mode="out-in">
                                    <div class="tab-pane active" key="2" v-if="login" id="login">
                                        <div class="mx-auto col-md-12 login-form-1">
                                            <form v-if="!restore" class="w-100" @submit.prevent="signInSend">
                                                <div class="form-group">
                                                    <input v-model="signIn.email" class="form-input-text-custom" placeholder="Email adress" type="text" value/>
                                                </div>
                                                <div class="form-group">
                                                    <input v-model="signIn.password" class="form-input-text-custom" placeholder="Password" type="password" value/>
                                                </div>
                                                <div class="form-group">
                                                    <input class="btnSubmit" type="submit" value="Log in"/>
                                                </div>
                                                <div class="form__tip">
                                                    <span>Forgot your password?</span>
                                                    <a @click.prevent="restore = !restore" href="#">Click here</a>
                                                </div>
                                            </form>
                                            <form @submit.prevent="restorePassword" v-if="restore" class="w-100">
                                                <div v-if="!restore_finish" class="form-group">
                                                    <input v-model="restore_email" class="form-input-text-custom" placeholder="Your email" type="text" value/>
                                                </div>
                                                <div v-if="restore_finish" class="form-group">
                                                    <input v-model="new_pass" class="form-input-text-custom" placeholder="New password" type="text" value/>
                                                </div>
                                                <div v-if="restore_finish" class="form-group">
                                                    <input v-model="conf_new_pass" class="form-input-text-custom" placeholder="Confirm new password" type="text" value/>
                                                </div>
                                                <div class="form-group">
                                                    <input class="btnSubmit" type="submit" value="Restore password"/>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="tab-pane active" id="sign-up" key="1" v-if="!login">
                                        <div class="mx-auto col-md-12 login-form-1">
                                            <form v-if="!confirm" class="w-100" @submit.prevent="signUpSend">
                                                <div class="form-group">
                                                    <input class="form-input-text-custom" placeholder="Full name" type="text" v-model="signUp.name" value/>
                                                </div>
                                                <div class="form-group">
                                                    <input v-model="signUp.email" class="form-input-text-custom" placeholder="Email adress" type="text" value/>
                                                </div>
                                                <div class="form-group">
                                                    <input v-model="signUp.password" class="form-input-text-custom" placeholder="Password" type="password" value/>
                                                </div>
                                                <div class="form-group auth__terms">
                                                    <label for>
                                                        <input class="btnSubmit" type="checkbox" v-model="signUp.terms" v-on:click="signUp.terms = !signUp.terms"/>I accept Terms & Conditions
                                                    </label>
                                                    <label for>
                                                        <input class="btnSubmit" type="checkbox" v-model="signUp.news" v-on:click="signUp.news = !signUp.news"/>I'd like to receive latest news about new
                                                        features!
                                                    </label>
                                                </div>
                                                <div class="form-group">
                                                    <input class="btnSubmit" type="submit" value="Create account now"/>
                                                </div>
                                            </form>
                                            <form @submit.prevent="confirmRegistration" v-if="confirm" class="w-100">
                                                <div class="form-group">
                                                    <input v-model="confirm_id" class="form-input-text-custom" placeholder="Confirmation code" type="text" value/>
                                                </div>
                                                <div class="form-group">
                                                    <input class="btnSubmit" type="submit" value="Confirm"/>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </transition>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: "Auth",
    data() {
        return {
            login: true,
            restore: false,
            restore_finish: false,
            confirm: false,
            signIn: {
                email: "",
                password: "",
                remember: "",
            },
            signUp: {
                name: "",
                email: "",
                password: "",
                terms: false,
                news: false,
            },
            confirm_id: '',
            restore_email: '',
            is_error: false,
            is_success: false,
            error: '',
            new_pass: '',
            conf_new_pass: '',
        };
    },
    mounted() {
        // if (typeof restore_code !== 'undefined') {
        //     this.restore = true;
        //     this.restore_finish = true;
        // }
    },
    methods: {
        changeAuth: function (auth) {
            this.is_error = false;
            
            switch (auth) {
                case "In":
                    this.login = true;
                    break;
                case "Up":
                    this.login = false;
                    break;
            }
        },
        restorePassword: function () {
            
            this.is_error = false;
            
            if (this.restore_email) {
                Vue.axios.post('/restore_request', {email: this.restore_email}).then(responce => {
                    if (responce.data.status === 200) {
                        this.login = true;
                        this.restore = false;
                    }
                    this.error = responce.data.message;
                    this.is_error = true;
                });
            }
            
            if (this.restore_finish) {
                if (!this.new_pass || !this.conf_new_pass) {
                    this.error = 'All fields are required';
                    this.is_error = true;
                    return;
                }
                
                if (this.new_pass !== this.conf_new_pass) {
                    this.error = 'Passwords do not match';
                    this.is_error = true;
                    return;
                }
                
                Vue.axios.post('/change_password', {new_pass: this.new_pass}).then(responce => {
                    if (responce.data.status === 200) {
                        this.login = true;
                        this.restore = false;
                    }
                    this.error = responce.data.message;
                    this.is_error = true;
                });
                
            }
            
        },
        signInSend: function () {
            this.is_error = false;
            this.error = false;
            Vue.axios.post('/signin', this.signIn).then(responce => {
                if (responce.data.status == 200) {
                    this.$router.push('/dashboard');
                }
                this.is_error = true;
                this.error = responce.data.message;
            });
        },
        signUpSend: function () {
            this.is_error = false;
            
            Vue.axios.post('/signup', this.signUp).then(responce => {
                if (responce.data.status == 200) {
                    this.is_error = false;
                    this.confirm = true;
                    return;
                }
                this.is_error = true;
                this.error = responce.data.message;
            });
            
        },
        confirmRegistration: function () {
            this.is_error = false;
            if (!this.confirm_id) {
                this.error = 'Code is required'
                this.is_error = true;
                return;
            }
            
            Vue.axios.post('/confirm', {token: this.confirm_id}).then(responce => {
                if (responce.data.status == 200) {
                    this.error = 'Success. Please log in'
                    this.is_success = true;
                    this.login = true;
                    return;
                }
                this.is_error = true;
                this.error = responce.data.message;
            });
        }
    },
};
</script>

