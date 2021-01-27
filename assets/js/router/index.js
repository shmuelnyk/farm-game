import Vue from "vue";
import Router from "vue-router";
import store from "@/store";
import routes from "./routes";

Vue.use(Router);

const router = new Router({
  routes,
  mode: "history",
  base: __dirname,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) return savedPosition; // Saved Position
    if (to.name === from.name) return; // Same page
    return {
      x: 0,
      y: 0
    };
  }
});

router.beforeEach((to, from, next) => {
  let docName = `Quiz`;
  if (to.name) {
    docName = `Quiz | ${to.name.replace(/\b\w/g, l =>
      l.toUpperCase()
    )}`;
  }

  document.body.className = to.meta.bodyClass || to.name;
  document.title = docName;

  if (to.matched.some(record => record.meta.requiresAuth)) {
    // this route requires auth, check if logged in
    // if not, redirect to login page.
    if (!store.getters["auth/isAuthenticated"]) {
      next({
        name: "login",
      });
    } else {
      next();
    }
  } else {
    next();
  }
});

export default router;
