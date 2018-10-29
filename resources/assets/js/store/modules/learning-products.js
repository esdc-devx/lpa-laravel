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
    organizationalUnits: [],
    projectLearningProducts: []
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
    },

    projectLearningProducts(state) {
      return state.projectLearningProducts;
    }
  },

  actions: {
    async loadLearningProducts({ commit, dispatch }) {
      await LearningProductAPI.getLearningProducts();
      commit('setLearningProducts', learningProducts);
    },

    async loadProjectLearningProducts({ commit }, projectId) {
      await LearningProductAPI.getProjectLearningProducts(projectId);
      commit('setProjectLearningProducts', projectLearningProducts);
    },

    async loadLearningProduct({ commit }, id) {
      await LearningProductAPI.getLearningProduct(id);
      commit('setViewing', learningProduct);
    },

    async loadLearningProductCreateInfo({ commit }) {
      let learningProductCreateInfo = await LearningProductAPI.getCreateInfo();
      commit('setOrganizationalUnits', learningProductCreateInfo.organizational_units);
      return learningProductCreateInfo;
    },

    async canCreate({ commit }) {
      let autorization = await LearningProductAPI.canCreate();
      return autorization;
    },

    async create({ commit }, learningProduct) {
      let newLearningProduct = await LearningProductAPI.create(learningProduct);
      commit('setViewing', newLearningProduct);
      return newLearningProduct;
    },

    async canDelete({ commit }, id) {
      let autorization = await LearningProductAPI.canDelete(id);
      return autorization;
    },

    async delete({ commit }, id) {
      await LearningProductAPI.delete(id);
    },

    async canEdit({ commit }, id) {
      let autorization = await LearningProductAPI.canEdit(id);
      return autorization;
    },

    async loadLearningProductEditInfo({ commit }, id) {
      let response = await LearningProductAPI.getEditInfo(id);
      commit('setViewing', response.learning_product);
      return response;
    }
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
    },

    setProjectLearningProducts(state, projectLearningProducts) {
      state.projectLearningProducts = projectLearningProducts;
    }
  }
};
