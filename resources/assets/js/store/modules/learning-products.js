import LearningProductAPI from '@api/learning-products';
import { helpers } from '@/helpers.js';

export default {
  namespaced: true,

  state: {
    // learning product being viewed
    viewing: {
      name: ''
    },
    all: [],
    //organizationalUnits: []
  },

  getters: {
    all(state) {
      return state.all;
    },

    viewing(state) {
      return state.viewing;
    },

    // organizationalUnits(state) {
    //   return state.organizationalUnits;
    // }
  },

  actions: {
    async loadLearningProducts({ commit, dispatch }) {
      let response = await LearningProductAPI.getLearningProducts();
      commit('setLearningProducts', response.data.data.learning_products);
    },

    async loadLearningProduct({ commit }, id) {
      let response = await LearningProductAPI.getLearningProduct(id);
      commit('setViewing', response.data.data.learning_product);
      return response.data.data;
    },

    // async canEditLearningProduct({ commit }, id) {
    //   //let response = await LearningProductAPI.canEdit(id);
    //   //return response.data.data.allowed;
    //   return false;
    // },

    // async canDeleteLearningProduct({ commit }, id) {
    //   // let response = await LearningProductAPI.canDelete(id);
    //   // return response.data.data.allowed;
    //   return false;
    // },
  },

  mutations: {
    setLearningProducts(state, learningProducts) {
      state.all = learningProducts;
    },

    setViewing(state, learningProduct) {
      state.viewing = learningProduct;
    },

    // setOrganizationalUnits(state, organizationalUnits) {
    //   state.organizationalUnits = organizationalUnits.sort((a, b) => helpers.localeSort(a, b, 'name'));
    // }
  }
};
