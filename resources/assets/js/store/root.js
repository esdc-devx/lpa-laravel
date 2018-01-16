import Vue from 'vue';
import axios from '../interceptor';
import * as types from './mutations-types';

export const state = {
  // since browser lang is based on locale-Country
  // we only need the locale
  language: localStorage.language || navigator.language.split('-')[0] || 'en',
  languages: []
};

export const getters = {
  getLanguage(state) {
    return state.language;
  },
  getLanguages(state) {
    return state.languages;
  }
};

export const actions = {
  switchI18n({ commit }, context) {
    commit(types.SET_LANGUAGE, context);
  },
  loadLanguages({ commit }, context) {
    return new Promise((resolve, reject) => {
      axios.get('/locales')
        .then(response => {
            state.languages = response.data;
            commit(types.SET_LANGUAGE, context);
            resolve(response.data);
          }
        )
        .catch(e => {
          reject(e);
        });
    });
  }
};

export const mutations = {
  [types.SET_LANGUAGE](state, lang) {
    // will set the language
    // based on the localstorage, navigator or default locale
    // if undefined
    lang = lang || state.language;

    // make sure that when i18n is init,
    // that we change its locale
    // so that it affects ElementUI's locale
    if (window.i18n) {
      window.i18n.locale = lang;
    }
    state.language = lang;
    localStorage.setItem('language', lang);
    axios.defaults.headers.common['Accept-Language'] = lang;
    document.querySelector('html').setAttribute('lang', lang);
  }
};
