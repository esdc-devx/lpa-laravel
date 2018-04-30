import Vue from 'vue';
import { Validator } from 'vee-validate';
import axios from '../axios/interceptor';
import * as types from './mutations-types';
import Config from '../config';

export const state = {
  language: Config.DEFAULT_LANG,
  languages: [],
  isAdminBarShown: false,
  isAppLoading: false,
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

  isAppLoading(state) {
    return state.isAppLoading;
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
      response = await axios.get('locales')
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
  showAppLoading({ commit }, context) {
    commit(types.TOGGLE_APP_LOADING, true);
  },

  hideAppLoading({ commit }, context) {
    commit(types.TOGGLE_APP_LOADING, false);
  },

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
  },

  [types.SET_LANGUAGES](state, languages) {
    state.languages = languages;
  },

  [types.TOGGLE_ADMINBAR](state, isShown) {
    isShown = !_.isUndefined(isShown) ? isShown : !state.isAdminBarShown;
    state.isAdminBarShown = isShown;
  },

  [types.TOGGLE_APP_LOADING](state, isShown) {
    let spinner = document.querySelectorAll('.loading-spinner')[0];
    if (spinner) {
      if (!isShown) {
        spinner.classList.add('fade-out');
        spinner.classList.remove('fade-in');
      } else {
        spinner.classList.add('fade-in');
        spinner.classList.remove('fade-out');
      }
    }

    // wait until the CSS transition has finished
    _.delay(() => {
      state.isAppLoading = isShown;
    }, 200);
  },

  [types.TOGGLE_MAIN_LOADING](state, isShown) {
    state.isMainLoading = isShown;
  }
};
