import ListsAPI from '@api/lists';

export default {
  namespaced: true,

  state: {
    lists: {
      en: {},
      fr: {}
    }
  },

  getters: {
    list(state) {
      return function (name) {
        let language = store.state.language;
        return state.lists[language][name] ? state.lists[language][name] : [];
      };
    }
  },

  actions: {
    async loadList({ commit, state }, listName) {
      let language = store.state.language;
      // do not reload the same list twice
      let listNameDiff = _.difference([listName], _.keys(state.lists[language]));
      if (listNameDiff.length) {
        let response = await ListsAPI.getList(listNameDiff);
        commit('setList', {
          name: listName,
          list: response.data
        });
      }
    },

    async loadLists({ commit, state }, listNames) {
      let language = store.state.language;
      // do not reload the same list twice
      let listNamesDiff = _.difference(listNames, _.keys(state.lists[language]));
      if (listNamesDiff.length) {
        let response = await ListsAPI.getLists(listNamesDiff);
        commit('setLists', response.data);
      }
    }
  },

  mutations: {
    setList(state, { name, list }) {
      let language = store.state.language;
      state.lists[language] = {...state.lists[language], [name]: list};
    },
    setLists(state, lists) {
      let language = store.state.language;
      state.lists[language] = {...state.lists[language], ...lists};
    }
  }
};
