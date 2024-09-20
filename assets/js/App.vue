<template>
  <div id="app">
    <Header></Header>
    <transition name="fade">
      <router-view />
    </transition>
    <transition name="fade">
      <Loader v-if="loading" />
    </transition>
    <Footer></Footer>
  </div>
</template>

<script>

import Loader from "./components/Loader";

import _ from "lodash";
import { mapGetters } from "vuex";
export default {
  name: "app",
  components: { Loader },
  computed: mapGetters({
    isAuthenticated: "auth/isAuthenticated"
  }),
  data() {
    return {
      loading: false
    };
  },
  methods: {
    logout() {
      this.$store.dispatch("auth/logout");
    },
    onResize() {
      // this.$store.commit("viewport/SET_SIZE", window.innerWidth);
    },
    // done: _.debounce(function(e) {
    //   this.loading = false;
    // }, 1000),
    done() {
      this.loading = false;
    },
    start() {
      this.loading = true;
    }
  },
  created() {
    this.$axios.interceptors.request.use(config => {
      // NProgress.start();
      this.start();
      return config;
    });

    // before a response is returned stop nprogress
    this.$axios.interceptors.response.use(
      response => {
        // NProgress.done();
        this.done();
        return response;
      },
      err => {
        // NProgress.done();
        this.done();
        return new Promise(function(resolve, reject) {
          if (
            err.status === 401 &&
            err.config &&
            !err.config.__isRetryRequest
          ) {
            this.logout();
          }
          throw err;
        });
        // return Promise.reject(err);
      }
    );

    // init viewport
    this.$nextTick(function() {
      window.addEventListener("resize", this.onResize);
    });

    if (this.isAuthenticated) {

    }
  }
};
</script>
