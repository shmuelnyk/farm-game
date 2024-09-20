import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import Cookies from "js-cookie";
import API from "@/services/API";
import "@/plugins/antd-ui";
import "intro.js/intro.js";
import Antd from 'ant-design-vue';
import 'ant-design-vue/dist/antd.css';
import '../css/app.css';
Vue.config.productionTip = false;

Vue.prototype.$axios = API;
Vue.use(Antd);

const accessToken = Cookies.get("quiz-auth");
if (accessToken) {
  Vue.prototype.$axios.defaults.headers.common = {
    Authorization: `bearer ${accessToken}`
  };
}

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount("#app");
