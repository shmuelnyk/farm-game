import Login from '@/pages/Login.vue';
import NotFound from '@/pages/404.vue';
import Index from '@/pages/Index.vue';
import Admin from '@/pages/Admin.vue';

const load = component => {
  // '@' is aliased to src/components
  return () => import(/* webpackChunkName: "[request]" */ `@/pages/${component}.vue`);
};

export default [
  {
    path: '/',
    name: 'index',
    component: Index,
    meta: {
      requiresAuth: false
    }
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: {
      requiresAuth: false
    }
  },
  {
    path: '/admin',
    name: 'admin',
    component: Admin,
    meta: {
      requiresAuth: true
    }
  },
  {
    // Redirect user to supported lang version.
    path: '*',
    name: '404',
    component: NotFound,
    meta: {
      requiresAuth: true
    }
  }
];
