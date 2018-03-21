import Vue from 'vue';
import { Validator } from 'vee-validate';
import axios from '../axios/interceptor';
import * as types from './mutations-types';
import EventBus from '../helpers/event-bus';
import Config from '../config';

export const state = {
  language: Config.defaultLang,
  languages: [],
  isAdminBarShown: false,
  isMainLoading: false
};

export const getters = {
  language(state) {
    return state.language;
  },

  languages(state) {
    return state.languages;
  },

  isAdminBarShown(state) {
    return state.isAdminBarShown;
  },

  isMainLoading(state) {
    return state.isMainLoading;
  }
};

export const actions = {
  // Localization handlers
  setLanguage({ commit }, context) {
    commit(types.SET_LANGUAGE, context);
  },

  async loadLanguages({ commit }, context) {
    let response;
    try {
      response = await axios.get('/locales')
      commit(types.SET_LANGUAGES, response.data.data);
      return response.data.data;
    } catch(e) {
      throw e;
    }
  },

  // Admin handlers
  toggleAdminBar({ commit }, context) {
    commit(types.TOGGLE_ADMINBAR, context);
  },

  // Loading handlers
  showMainLoading({ commit }, context) {
    commit(types.TOGGLE_MAIN_LOADING, true);
  },

  hideMainLoading({ commit }, context) {
    commit(types.TOGGLE_MAIN_LOADING, false);
  }
};

export const mutations = {
  [types.SET_LANGUAGE](state, lang = state.language) {
    state.language = lang;
    localStorage.setItem('language', lang);

    Validator.localize(lang);

    // reflect the language in the lang attribute
    // for accessibility purposes
    document.querySelector('html').lang = lang;

    EventBus.$emit('Store:languageUpdate', lang);
  },

  [types.SET_LANGUAGES](state, languages) {
    state.languages = languages;
  },

  [types.TOGGLE_ADMINBAR](state, isShown) {
    isShown = !_.isUndefined(isShown) ? isShown : !state.isAdminBarShown;
    state.isAdminBarShown = isShown;
  },

  [types.TOGGLE_MAIN_LOADING](state, isShown) {
    state.isMainLoading = isShown;
  }
};
