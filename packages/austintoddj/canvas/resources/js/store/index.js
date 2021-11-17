import Vue from 'vue';
import Vuex from 'vuex';
import search from './modules/search';
import settings from './modules/settings';

Vue.use(Vuex);

export const store = new Vuex.Store({
    modules: {
        search,
        settings,
    },
});
