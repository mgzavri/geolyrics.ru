require('./bootstrap')
import Vue from "vue"

import Vuetify from 'vuetify'



import router from "./router"
import store from './store'
import axios from "axios"

Vue.component('app', require('./components/App.vue').default)
Vue.component('topmenu', require('./components/Topmenu.vue').default)

Vue.use(Vuetify)

const app = new Vue({
    el: '#app',
    router,
    store,
    axios,
    vuetify: new Vuetify({
        theme: {
            themes: {
                light: {
                    primary: '#e8112d',
                    secondary: '#b0bec5',
                    accent: '#790202',
                    error: '#b71c1c',
                },
            },
        },
    })
});
