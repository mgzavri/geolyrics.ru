<template>
    <v-app app>
        <v-main>
            <topmenu></topmenu>
            <router-view>

            </router-view>
        </v-main>
    </v-app>
</template>

<script>
import {mapActions} from "vuex";

export default {
    name: "App",
    mounted() {
this.LOAD_LETTERS()
    },
    methods:{
        ...mapActions(['LOAD_LETTERS']),

    }
}
</script>

<style scoped>

</style>
