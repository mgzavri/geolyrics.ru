<template>
<div class="container">

    <Artists></Artists>

</div>


</template>

<script>
import {mapGetters} from 'vuex'
import Artists from "../components/Artists";
export default {
    name: "Lyrics",
    components:{
        Artists
    },
    computed:{
        ...mapGetters(['TEXTS']),
    }

}
</script>

<style scoped>

</style>
