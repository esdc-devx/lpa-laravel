import LearningProductAPI from '@api/learning-products';

import LearningProduct from '../models/Learning-Product';

import { helpers } from '@/helpers.js';

export default {
  namespaced: true,

  state: {
    codes: [],
    projects: [],
    organizationalUnits: [],
    projectLearningProducts: []
  },

  getters: {
    isCourse: state => id => {
      id = id || router.currentRoute.params.entityId;
      const learningProduct = LearningProduct.find(id);
      return learningProduct.type.name_key === 'course';
    },
    isCourseInstructorLed: state => id => {
      id = id || router.currentRoute.params.entityId;
      const learningProduct = LearningProduct.find(id);
      return learningProduct.type.name_key === 'course' &&
             learningProduct.sub_type.name_key === 'instructor-led';
    },
    isCourseDistance: state => id => {
      id = id || router.currentRoute.params.entityId;
      const learningProduct = LearningProduct.find(id);
      return learningProduct.type.name_key === 'course' &&
             learningProduct.sub_type.name_key === 'distance-learning';
    },
    isCourseOnlineSelfPaced: state => id => {
      id = id || router.currentRoute.params.entityId;
      const learningProduct = LearningProduct.find(id);
      return learningProduct.type.name_key === 'course' &&
             learningProduct.sub_type.name_key === 'online-self-paced';
    }
  },

  actions: {
    async $$create({ commit }, learningProduct) {
      const response = await LearningProductAPI.create(learningProduct);
      LearningProduct.create({ data: response.data });
      return response.data;
    },

    async $$update({ commit }, { id, data }) {
      const response = await LearningProductAPI.update(id, data);
      LearningProduct.insertOrUpdate({ data: response.data });
    },

    async $$delete({ commit }, id) {
      await LearningProductAPI.delete(id);
      LearningProduct.delete(id);
    },

    async load({ commit }, id) {
      const response = await LearningProductAPI.get(id);
      LearningProduct.insertOrUpdate({ data: response.data.learning_product });
    },

    async loadAll({ commit, dispatch }) {
      const response = await LearningProductAPI.getAll();
      LearningProduct.create({ data: response.data.learning_products });
    },

    async loadProjectLearningProducts({ commit }, projectId) {
      const response = await LearningProductAPI.getProjectLearningProducts(projectId);
      commit('setProjectLearningProducts', response.data.learning_products);
    },

    async loadCodes({ commit }) {
      const response = await LearningProductAPI.getCodes();
      commit('setCodes', response.data);
    },

    async search({ commit }, code) {
      return await LearningProductAPI.search(code);
    },

    async loadCreateInfo({ commit }) {
      const response = await LearningProductAPI.getCreateInfo();
      commit('setProjects', response.data.projects);
      commit('setOrganizationalUnits', response.data.organizational_units);
    },

    async loadEditInfo({ commit }, id) {
      const response = await LearningProductAPI.getEditInfo(id);
      LearningProduct.insertOrUpdate({ data: response.data.learning_product });
      commit('setOrganizationalUnits', response.data.organizational_units);
    }
  },

  mutations: {
    setCodes(state, codes) {
      state.codes = codes;
    },

    setProjects(state, projects) {
      state.projects = projects;
    },

    setOrganizationalUnits(state, organizationalUnits) {
      state.organizationalUnits = organizationalUnits.sort((a, b) => helpers.localeSort(a, b, 'name'));
    },

    setProjectLearningProducts(state, projectLearningProducts) {
      state.projectLearningProducts = projectLearningProducts;
    }
  }
};
