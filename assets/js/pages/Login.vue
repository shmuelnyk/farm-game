<template>
    <a-form-model layout="inline" :model="login" @submit="onSubmitLogin" @submit.native.prevent>
        <img :src="logo" alt="" style="max-width: 20vw">
        <h1 style="text-align: center">Login</h1>
        <a-input v-model="login.email" placeholder="Email" style="width: 100%">
            <a-icon slot="prefix" type="user" style="color:rgba(0,0,0,.25)"/>
        </a-input>
        <br>
        <a-input v-model="login.password" type="password" placeholder="Password" style="width: 100%">
            <a-icon slot="prefix" type="lock" style="color:rgba(0,0,0,.25)"/>
        </a-input>
        <br>
        <a-button
                type="primary"
                html-type="submit"
                :disabled="login.user === '' || login.password=== ''"
        >
            Log in
        </a-button>
    </a-form-model>
</template>

<script>
    import {mapState} from "vuex";


    export default {
        components: {},
        computed: mapState({
            status: state => state.auth.status

        }),
        data() {
            return {
                showPassword: false,
                logo: require("~/images/logo.png"),
                login: {
                    email: '',
                    password: ''
                },
                loginError: {
                    email: false,
                    password: false
                },
            };
        },
        methods: {
            onSubmitLogin() {
                console.log('submit...')
                this.$store.dispatch("auth/login", this.login).then(() => {
                    this.$router.push({name: "admin"});
                })
            }
        },
        mounted() {

        }
    };
</script>

<style lang="scss" scoped>
    .ant-form {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        border: 1px solid grey;
        min-height: 300px;
        padding: 50px;
        border-radius: 20px;
        display: flex;
        flex-direction: column;
        text-align: center;
        justify-content: space-between;
    }
</style>