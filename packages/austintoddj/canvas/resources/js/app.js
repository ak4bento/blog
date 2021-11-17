import { store } from './store';
import Toasted from 'vue-toasted';
import Vue from 'vue';
import moment from 'moment';
import request from './mixins/request';
import router from './router';

require('bootstrap');

window.Popper = require('popper.js').default;

Vue.prototype.moment = moment;

Vue.use(Toasted, {
    position: 'bottom-right',
    theme: 'bubble',
    duration: 2500,
});

Vue.mixin(request);

Vue.config.productionTip = false;

new Vue({
    el: '#canvas',
    router,
    store,
});
