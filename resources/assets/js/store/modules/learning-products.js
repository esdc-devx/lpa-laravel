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
    organizationalUnits: []
  },

  getters: {
    all(state) {
      return state.all;
    },

    viewing(state) {
      return state.viewing;
    },

    organizationalUnits(state) {
      return state.organizationalUnits;
    }
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

    async loadLearningProductCreateInfo({ commit }) {
      let response = await LearningProductAPI.getLearningProductCreateInfo();
      commit('setOrganizationalUnits', response.data.data.organizational_units);
      return response.data.data;
    },

    async canCreateLearningProduct({ commit }) {
      let response = await LearningProductAPI.canCreateLearningProduct();
      return response.data.data.allowed;
    },

    async create({ commit }, learningProduct) {
      let response = await LearningProductAPI.create(learningProduct);
      commit('setViewing', response.data.data);
      return response.data.data;
    },
  },

  mutations: {
    setLearningProducts(state, learningProducts) {
      state.all = learningProducts;
    },

    setViewing(state, learningProduct) {
      state.viewing = learningProduct;
    },

    setOrganizationalUnits(state, organizationalUnits) {
      state.organizationalUnits = organizationalUnits.sort((a, b) => helpers.localeSort(a, b, 'name'));
    }
  }
};
