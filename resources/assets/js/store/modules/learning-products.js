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
    projects: [],
    permissions: {
      canCreate: false,
      canEdit: false,
      canDelete: false
    },
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
      let response = await LearningProductAPI.getLearningProducts();
      commit('setLearningProducts', response.data.learning_products);
    },

    async loadProjectLearningProducts({ commit }, projectId) {
      let response = await LearningProductAPI.getProjectLearningProducts(projectId);
      commit('setProjectLearningProducts', response.data.learning_products);
    },

    async loadLearningProduct({ commit }, id) {
      let response = await LearningProductAPI.getLearningProduct(id);
      commit('setViewing', response.data.learning_product);
    },

    async loadLearningProductCreateInfo({ commit }) {
      let response = await LearningProductAPI.getCreateInfo();
      commit('setProjects', response.data.projects);
      commit('setOrganizationalUnits', response.data.organizational_units);
    },

    async loadLearningProductEditInfo({ commit }, id) {
      let response = await LearningProductAPI.getEditInfo(id);
      commit('setViewing', response.data.learning_product);
      commit('setOrganizationalUnits', response.data.organizational_units);
    },

    async create({ commit }, learningProduct) {
      let response = await LearningProductAPI.create(learningProduct);
      commit('setViewing', response.data);
    },

    async update({ commit }, { id , data }) {
      await LearningProductAPI.update(id, data);
    },

    async delete({ commit }, id) {
      await LearningProductAPI.delete(id);
    },

    async loadCanCreate({ commit }) {
      let response = await LearningProductAPI.canCreate();
      commit('setPermission', {
        name: 'canCreate',
        isAllowed: response.data.allowed
      });
    },

    async loadCanEdit({ commit }, id) {
      let response = await LearningProductAPI.canEdit(id);
      commit('setPermission', {
        name: 'canEdit',
        isAllowed: response.data.allowed
      });
    },

    async loadCanDelete({ commit }, id) {
      let response = await LearningProductAPI.canDelete(id);
      commit('setPermission', {
        name: 'canDelete',
        isAllowed: response.data.allowed
      });
    }
  },

  mutations: {
    setLearningProducts(state, learningProducts) {
      state.all = learningProducts;
    },

    setViewing(state, learningProduct) {
      state.viewing = learningProduct;
    },

    setProjects(state, projects) {
      state.projects = projects;
    },

    setPermission(state, { name, isAllowed }) {
      state.permissions[name] = isAllowed;
    },

    setOrganizationalUnits(state, organizationalUnits) {
      state.organizationalUnits = organizationalUnits.sort((a, b) => helpers.localeSort(a, b, 'name'));
    },

    setProjectLearningProducts(state, projectLearningProducts) {
      state.projectLearningProducts = projectLearningProducts;
    }
  }
};
