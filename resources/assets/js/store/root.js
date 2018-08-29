import Vue from 'vue';

import EventBus from '@/event-bus';

import { Validator } from 'vee-validate';
import axios from '@axios/interceptor';
import * as types from './mutations-types';
import Config from '@/config';

export const state = {
  language: Config.DEFAULT_LANG,
  languages: [],
  shouldConfirmBeforeLanguageChange: false,
  shouldConfirmBeforeLeaving: false,
  isAdminBarShown: false,
  isAppLoading: false,
  isMainLoading: false,
  mainLoadingCount: 0
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
  },

  mainLoadingCount(state) {
    return state.mainLoadingCount;
  },

  shouldConfirmBeforeLanguageChange(state) {
    return state.shouldConfirmBeforeLanguageChange;
  },

  shouldConfirmBeforeLeaving(state) {
    return state.shouldConfirmBeforeLeaving;
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
      response = await axios.get('locales');
      commit(types.SET_LANGUAGES, response.data.data);
      return response.data.data;
    } catch (e) {
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

  // This is how it goes when we show a main loading.
  // For example:
  // user toggles language, showMainLoadingCount = 1
  // sends an event that language is toggling,
  // components show main loading and fetch data, showMainLoadingCount = 2
  // route is transitioned, language is set and hideMainLoading is called, showMainLoadingCount = 1
  // component fetch is done, showMainLoadingCount = 0 => hideMainLoading
  showMainLoading({ commit, state }, context) {
    // grab the actual mainLoadingCount value and increase it
    let count = state.mainLoadingCount + 1;
    commit(types.MAIN_LOADING_COUNT, count);
    commit(types.TOGGLE_MAIN_LOADING, true);
  },

  hideMainLoading({ commit }, context) {
    let countSupposedToBe = state.mainLoadingCount - 1;
    // grab the actual mainLoadingCount value and decrease it
    let count = (state.mainLoadingCount - 1) < 0 ? 0 : state.mainLoadingCount - 1;
    commit(types.MAIN_LOADING_COUNT, count);
    if (count === 0) {
      // only hide the main loading if the count is equal to 0
      // meaning that we hit the last showMainLoading call
      commit(types.TOGGLE_MAIN_LOADING, false);
    // check if we have hideLoading leftovers
    }
    if (countSupposedToBe < 0) {
      Vue.$log.warn(`Too many calls to hideMainLoading.`);
    }
  },

  confirmBeforeLanguageChange({ commit }, context) {
    commit(types.SHOULD_CONFIRM_BEFORE_LANGUAGE_CHANGE, context);
  },

  confirmBeforeLeaving({ commit }, context) {
    commit(types.SHOULD_CONFIRM_BEFORE_LEAVING, context);
  }
};

export const mutations = {
  [types.SET_LANGUAGE](state, lang = state.language) {
    state.language = lang;
    localStorage.setItem('language', lang);

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
    EventBus.$emit('Store:languageUpdate', lang);
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

    state.isAppLoading = isShown;
  },

  [types.TOGGLE_MAIN_LOADING](state, isShown) {
    state.isMainLoading = isShown;
    // Workaround until this is properly fixed by ElementUI
    // https://github.com/ElemeFE/element/issues/8894
    // Everytime a loading mask is hidden (!isShown)
    // this basically wait until the next rendering is done,
    // give us 1sec of buffer so that we don't alter the loading's mask's display
    // and force hide the loading mask so that there is no leftover on IE11 that would block the entire UI.
    Vue.nextTick(() => {
      _.delay(() => {
        if (!isShown) {
          document.querySelector('.content-wrap .el-loading-mask').style['display'] = 'none';
        }
      }, 1000);
    })
  },

  [types.MAIN_LOADING_COUNT](state, count) {
    state.mainLoadingCount = count;
  },

  [types.SHOULD_CONFIRM_BEFORE_LANGUAGE_CHANGE](state, val) {
    state.shouldConfirmBeforeLanguageChange = val;
  },

  [types.SHOULD_CONFIRM_BEFORE_LEAVING](state, val) {
    state.shouldConfirmBeforeLeaving = val;
  }
};
