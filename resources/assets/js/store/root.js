import Vue from 'vue';
import axios from '../interceptor';
import * as types from './mutations-types';
import EventBus from '../components/event-bus';

export const state = {
  // since browser lang is based on locale-Country
  // we only need the locale
  language: document.querySelector('html').lang,
  languages: []
};

export const getters = {
  language(state) {
    return state.language;
  },
  languages(state) {
    return state.languages;
  }
};

export const actions = {
  setLanguage({ commit }, context) {
    commit(types.SET_LANGUAGE, context);
  },
  loadLanguages({ commit }, context) {
    return new Promise((resolve, reject) => {
      axios.get('/locales')
        .then(response => {
          commit(types.SET_LANGUAGES, response.data);
          resolve(response.data);
        })
        .catch(e => {
          reject(e);
        });
    });
  }
};

export const mutations = {
  [types.SET_LANGUAGE](state, lang) {
    // if undefined, take the default value
    lang = lang || state.language;

    // make sure that when i18n is init,
    // that we change its locale
    // so that it affects ElementUI's locale
    if (window.i18n) {
      window.i18n.locale = lang;
    }
    state.language = lang;
    localStorage.setItem('language', lang);

    // reflect the language in the lang attribute
    // for accessibility purposes
    document.querySelector('html').lang = lang;

    EventBus.$emit('Store:languageUpdate', lang);
  },
  [types.SET_LANGUAGES](state, languages) {
    state.languages = languages;
  }
};
