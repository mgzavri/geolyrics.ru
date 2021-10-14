<template>
    <div>
        <v-app-bar flat app class="blue-grey darken-2">
            <v-app-bar-nav-icon @click.stop="drawer = !drawer" v-if="$vuetify.breakpoint.mobile" class="text-white"></v-app-bar-nav-icon>
            <v-spacer></v-spacer>
            <v-spacer v-if="!$vuetify.breakpoint.mobile"></v-spacer>

            <v-toolbar-items v-if="!$vuetify.breakpoint.mobile">
                <v-btn class="text-white" text to="/lyrics">{{ TEXTS.TEXTS }}</v-btn>
                <v-btn class="text-white" text to="/music">{{ TEXTS.MUSIC }}</v-btn>
            </v-toolbar-items>

            <v-spacer></v-spacer>

            <v-toolbar-title class="text-xs-center">
                <router-link class="navbar-brand logo" to="/"><img alt="GeoLyrics" :src="images.logo"/>
                </router-link>
            </v-toolbar-title>

            <v-spacer></v-spacer>

            <v-toolbar-items v-if="!$vuetify.breakpoint.mobile">
                <v-btn text class="text-white" to="/georgian_language">{{ TEXTS.GEORGIAN_LANGUAGE }}</v-btn>
            </v-toolbar-items>
            <v-spacer></v-spacer>
            <v-toolbar-items v-if="!$vuetify.breakpoint.mobile" class="d-flex align-items-center justify-content-end">
                <div @click="SET_LANG('ru')" class="flag" :style="{ backgroundImage: 'url('+images.flag_ru+')'}" > </div>

                <div @click="SET_LANG('en')" class="flag" :style="{ backgroundImage: 'url('+images.flag_en+')'}" > </div>

                <div @click="SET_LANG('ge')" class="flag" :style="{ backgroundImage: 'url('+images.flag_ge+')'}" > </div>

            </v-toolbar-items>
        </v-app-bar>

        <v-toolbar-items class="flags--mobile" v-if="$vuetify.breakpoint.mobile">

            <div @click="SET_LANG('ru')" class="flag" :style="{ backgroundImage: 'url('+images.flag_ru+')'}" > </div>

            <div @click="SET_LANG('en')" class="flag" :style="{ backgroundImage: 'url('+images.flag_en+')'}" > </div>

            <div @click="SET_LANG('ge')" class="flag" :style="{ backgroundImage: 'url('+images.flag_ge+')'}" > </div>
        </v-toolbar-items>

        <v-navigation-drawer
            v-model="drawer"
            absolute
            temporary
        >
            <v-list-item>

                <v-list-item-content>
                    <v-list-item-title>Menu</v-list-item-title>
                </v-list-item-content>
            </v-list-item>

            <v-divider></v-divider>

            <v-list dense>
                <v-list-item link to="lyrics">
                    <v-list-item-icon>
                        <v-icon color="#e8112d">translate</v-icon>
                    </v-list-item-icon>

                    <v-list-item-content>
                        <v-list-item-title>{{ TEXTS.TEXTS }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item link to="music">
                    <v-list-item-icon>
                        <v-icon color="#e8112d">library_music</v-icon>
                    </v-list-item-icon>

                    <v-list-item-content>
                        <v-list-item-title>{{ TEXTS.MUSIC }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item link to="geolanguage">
                    <v-list-item-icon>
                        <v-img width="20px" height="20px" :src="images.geolang"></v-img>
                    </v-list-item-icon>

                    <v-list-item-content>
                        <v-list-item-title>{{ TEXTS.GEORGIAN_LANGUAGE }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>


    </div>
</template>


<script>

import {mapGetters, mapMutations} from 'vuex'

export default {
    name: "Topmenu",
    data: () => ({
        drawer: false,
        group: null,
    }),

    methods: {
        ...mapMutations(['SET_LANG'])
    },
    computed: {
        ...mapGetters(['TEXTS']),
        images() {
            return {
                logo: "/storage/images/assets/logo_light.png",
                geolang: "/storage/images/assets/flags/geoflag.png",
                flag_en: "/storage/images/assets/flags/english.png",
                flag_ge: "/storage/images/assets/flags/georgian.png",
                flag_ru: "/storage/images/assets/flags/russian.png"
            }
        }

    },

    watch: {
        group() {
            this.drawer = false
        }
    }
}

</script>
<style scoped lang="css">
a{text-decoration: none}
.flag {
    cursor: pointer;
    margin: 0 2px;
    width:40px;
    height:30px;
    background-size: contain;
    background-repeat: no-repeat;
}


.flags--mobile {
    display: flex;
    justify-content: center;
    z-index: 100;

}
</style>
