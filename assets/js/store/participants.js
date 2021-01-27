import API from "@/services/API";

export default {
  namespaced: true,
  strict: true,
  state: {
    items: [],
    pagination: {
      current: 1,
      pageSize: 10,
      total: 1,
    },
  },
  getters: {
    getList(state) {
      return state.items;
    },
    getPagination(state) {
      return state.pagination;
    },
  },
  mutations: {
    SET_ITEMS(state, items) {
      state.items = items;
    },
    SET_PAGINATION(state, pagination) {
      state.pagination = pagination;
    },
  },
  actions: {
    getItems({ state, getters, commit }, payload = {}) {
      let url = "/api/participants";
      let items = 10;
      if (payload.pagination) {
        items = payload.pagination.pageSize;
      }
      url = url + "?items=" + items;
      if (!payload.pagination) {
        if (state.pagination.current) {
          url = url + "&_page=" + state.pagination.current;
        } else {
          url = url + "&_page=1";
        }
      } else {
        if (payload.pagination.current) {
          url = url + "&_page=" + payload.pagination.current;
        } else {
          url = url + "&_page=1";
        }
      }
      return API.get(url)
          .then((res) => {
            let pagination = Object.assign({}, state.pagination);
            pagination.current = payload.pagination && payload.pagination.current ?payload.pagination.current :1;
            pagination.total = res.data["hydra:totalItems"];
            if (payload.pagination) {
              pagination.pageSize = payload.pagination.pageSize;
            }
            commit("SET_PAGINATION", pagination);
            commit("SET_ITEMS", res.data["hydra:member"]);
          })
          .catch((err) => {
            throw err.response.data.message;
          });
    },
    searchItems({ getters, commit }, payload = {}) {
      if (!payload.pagination.current) {
        payload.pagination.current = 1;
      }
      return API.post("/api/training_attendees/search", payload)
          .then((res) => {
            payload.pagination.total = res.data.total;
            commit("SET_PAGINATION", payload.pagination);
            commit("SET_ITEMS", res.data.data);


          })
          .catch((err) => {
            throw err.response.data.message;
          });
    },
  },
};