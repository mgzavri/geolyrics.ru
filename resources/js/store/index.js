import Vue from 'vue'
import Vuex from 'vuex'
import createPersistedState from "vuex-persistedstate"
import VueCookies from 'vue-cookies'

import lang from './lang'
import songs from './lyrics'
import views from "./views"

Vue.use(Vuex)
Vue.use(VueCookies)

export default new Vuex.Store({
    plugins: [createPersistedState({
        paths:['lang.lang','views.view'],
        storage: {
            getItem: key => $cookies.get(key),
            setItem: (key, value) => $cookies.set(key, value, { expires: 365, secure: true }),
            removeItem: key => $cookies.remove(key)
        }
    })],
    modules: {
        lang,
        songs,
        views
    }
})
