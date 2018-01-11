import Vue from "vue";
import axios from "axios";
import * as types from "./mutations_types";
import i18n from '../locale';

import { LPA_CONFIG } from '../config.js';

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
      axios.get(`${LPA_CONFIG.API_URL}/locales`)
        .then(response => {
            state.languages = response.data;
            commit(types.SET_LANGUAGE, context);
            resolve(response.data);
          }
        )
        .catch(e => {
          console.log(e);
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

    state.language = lang;
    localStorage.setItem("language", lang);
    axios.defaults.headers.common["Accept-Language"] = lang;
    document.querySelector("html").setAttribute("lang", lang);
  }
};
