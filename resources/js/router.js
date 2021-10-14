import Vue from 'vue'
import vueRouter from 'vue-router'
import Index from './components/Index'
import Page404 from './views/Page404'
import Lyrics from "./views/Lyrics"
import Artist from "./views/Artist"
import Song from "./views/Song"

Vue.use(vueRouter)

const routes = [
    {path: "/", component: Index, name: "Index"},
    {path: "/404", component: Page404, name: "404"},
    {path: "/lyrics", component: Lyrics, name: "Lyrics"},
    {path: "/lyrics/:artist", component: Artist, name: "Artist"},
    {path: "/lyrics/:artist/:song", component: Song, name: "Song"}
]

export default new vueRouter({
    mode: "history",
    routes
})
