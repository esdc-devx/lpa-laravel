import Vue from 'vue';
import { Validator } from 'vee-validate';
import axios from '../axios/interceptor';
import * as types from './mutations-types';
import EventBus from '../components/event-bus';
import Config from '../config';

export const state = {
  language: Config.defaultLang,
  languages: [],
  isAdminBarShown: false
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
  }
};

export const actions = {
  // Localization handlers
  setLanguage({ commit }, context) {
    commit(types.SET_LANGUAGE, context);
  },
  loadLanguages({ commit }, context) {
    return new Promise((resolve, reject) => {
      axios.get('/locales')
        .then(response => {
          commit(types.SET_LANGUAGES, response.data.data);
          resolve(response.data.data);
        })
        .catch(e => {
          reject(e);
        });
    });
  },

  // Admin handlers
  toggleAdminBar({ commit }, context) {
    commit(types.TOGGLE_ADMINBAR, context);
  }
};

export const mutations = {
  [types.SET_LANGUAGE](state, lang) {
    // if undefined, take the default value
    lang = lang || state.language;

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
  }
};
