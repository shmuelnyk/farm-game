import API from '@/services/API';
import jwt from 'jsonwebtoken';
import Cookies from 'js-cookie';

export default {
  namespaced: true,
  strict: true,
  state: {
    status: '',
    error: null,
    accessToken: Cookies.get('quiz-auth') || '',
  },
  getters: {
    isAuthenticated(state) {
      return !!state.accessToken;
    },
    isTokenExpire(state) {
      const decodeed = jwt.decode(state.accessToken);
      if (!decodeed) return;
      return new Date().getTime() > decodeed.exp * 1000;
    },
    isFirstLogin(state) {
      return state.mainVideo ? false : true;
    },
    getUser(state) {
      return jwt.decode(state.accessToken);
    }
  },
  mutations: {
    AUTHENTICATING(state) {
      state.status = 'loading';
    },
    SET_TOKEN(state, accessToken) {
      state.status = 'success';
      state.accessToken = accessToken;
    },
    AUTHENTICATING_ERROR(state, error) {
      state.status = 'error';
      state.error = error;
    },
    LOGOUT(state) {
      state.status = '';
      state.error = null;
      state.accessToken = '';
      state.user = {};
      Object.keys(Cookies.get()).forEach(function(cookieName) {
        Cookies.remove(cookieName);
      });
    }
  },
  actions: {
    login({ commit }, payload) {
      return API.post('/login_check', payload)
        .then(res => {
          console.log(res)
          const accessToken = res.data.token;

          Cookies.set('quiz-auth', accessToken, {
            expires: 8 / 24
          }); // 8h Follow lexik_jwt_authentication.yaml

          // 8h Follow lexik_jwt_authentication.yaml

          API.defaults.headers.common = {
            Authorization: `bearer ${accessToken}`
          };




          commit('SET_TOKEN', jwt.decode(accessToken));
        })
        .catch(err => {
          commit('AUTHENTICATING_ERROR', err.response.data.message);
          Object.keys(Cookies.get()).forEach(function(cookieName) {
            Cookies.remove(cookieName);
          });

          throw err.response.data.message;
        });
    },
    logout({ commit }) {
      return new Promise((resolve, reject) => {
        commit('LOGOUT');
        Object.keys(Cookies.get()).forEach(function(cookieName) {
          Cookies.remove(cookieName);
        });
        delete API.defaults.headers.common['Authorization'];
        resolve();
      });
    },
  }
};
