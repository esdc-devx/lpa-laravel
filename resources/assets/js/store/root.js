import { Validator } from 'vee-validate';
import axios from '@axios/interceptor';
import Config from '@/config';

import Constants from '@/constants.js';

export const state = {
  language: Config.DEFAULT_LANG,
  languages: [],
  filteredDataTableList: [],
  waitForLogout: false,
  shouldConfirmBeforeLeaving: false,
  isAppLoading: false,
  isMainLoading: false,
  mainLoadingCount: 0
};

export const getters = {};

export const actions = {
  async loadLanguages({ commit }, context) {
    let response;
    try {
      response = await axios.get('locales');
      commit('setLanguages', response.data);
      return response.data;
    } catch (e) {
      throw e;
    }
  },

  hideMainLoading({ commit }, context) {
    setTimeout(() => {
      if (state.mainLoadingCount === 1) {
        commit('toggleMainLoading', false);
      }
      commit('setMainLoadingCount', state.mainLoadingCount - 1);
    }, Constants.DELAY_HIDE_MAIN_LOADING);
  }
};

export const mutations = {
  setLanguage(state, lang = state.language) {
    state.language = lang;

    // change the locale of the translation plugin
    // this makes sure that when using the browser back-forward buttons
    // that the language is still propagated around the app
    if (window.i18n) {
      window.i18n.locale = lang;
    }

    Validator.localize(lang);

    // reflect the language in the lang attribute
    // for accessibility purposes
    document.querySelector('html').lang = lang;
  },

  setLanguages(state, languages) {
    state.languages = languages;
  },

  // Loading handlers
  showAppLoading(state, context) {
    this.commit('toggleAppLoading', true);
  },

  hideAppLoading(state, context) {
    this.commit('toggleAppLoading', false);
  },

  // This is how it goes when we show a main loading.
  // For example:
  // user toggles language, showMainLoadingCount = 1
  // sends an event that language is toggling,
  // components show main loading and fetch data, showMainLoadingCount = 2
  // route is transitioned, language is set and hideMainLoading is called, showMainLoadingCount = 1
  // component fetch is done, showMainLoadingCount = 0 => hideMainLoading
  showMainLoading(state, context) {
    // grab the actual mainLoadingCount value and increase it
    if (state.mainLoadingCount === 0) {
      this.commit('toggleMainLoading', true);
    }
    this.commit('setMainLoadingCount', state.mainLoadingCount + 1);
  },

  toggleAppLoading(state, isShown) {
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
    state.isAppLoading = isShown;
  },

  toggleMainLoading(state, isShown) {
    state.isMainLoading = isShown;
  },

  setMainLoadingCount(state, count) {
    state.mainLoadingCount = count;
  },

  setShouldConfirmBeforeLeaving(state, val) {
    state.shouldConfirmBeforeLeaving = val;
  },

  setWaitForLogout(state, waitForLogout) {
    state.waitForLogout = waitForLogout;
  },

  addFilteredDataTable(state, dataTableName) {
    if (_.indexOf(state.filteredDataTableList, dataTableName) === -1) {
      state.filteredDataTableList.push(dataTableName);
    }
  },

  deleteFilteredDataTable(state, dataTableName) {
    let index = _.indexOf(state.filteredDataTableList, dataTableName);
    if (index !== -1) {
      state.filteredDataTableList.splice(index, 1);
    }
  }
};
