import Vue from 'vue';
import Vuex from 'vuex';
import AuthModule from './auth';
import ParticipantModule from './participants';


Vue.use(Vuex);

export default new Vuex.Store({
  strict: true,
  modules: {
    auth: AuthModule,
    participants: ParticipantModule,

  }
});
