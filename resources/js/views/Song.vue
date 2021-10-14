<template>
    <div class="container">
        <div class="text-h4 primary p-2 text-white song_title">{{ title }}</div>
        <h5 class="text-right">
            <router-link class="text-h5 text-right" :to="'/lyrics/'+artist.alias+'.html'"
                         v-text="artistName"></router-link>
        </h5>
        <div class="audio" v-for="(audiofile, index) in audio" :key="index">
            <audio :src="'/storage/audio/'+audiofile.audio" controls preload="auto" class="player"/>
        </div>
<div class="tabs_container d-flex justify-content-end"><div class="tabs d-flex align-content-stretch"><div class="tab" v-for="(tab, index) in tabData.tabs" :key="index" v-text="tab"  /></div></div>

        <div class="lyrics d-flex justify-content-between" v-if="!$vuetify.breakpoint.mobile">



            <div class="lyrics_geo">
                <h1>{{ song.title_ge }}</h1>
                <div v-html="song.text_ge"></div>
            </div>



            <div class="lyrics_tabs">
                <div v-for="(tab, index) in tabData.tabItems" :key="index" v-html="tab" />
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters} from 'vuex'

export default {
    name: "Song",
    data() {
        return {
            song: {},
            artist: {},
            audio: [],
            rutext: '',
            rutitle: '',
            entext: '',
            entitle: '',
            tabData:{},
            loading: true
        }
    },
    async mounted() {
        let alias = this.$route.params.song.split('.')[0]
        await axios
            .get('/api/lyrics/' + alias)
            .then(response => {
                if (response.data.status === 'false') {
                    return
                } else {
                    this.loading = false
                    this.song = response.data
                    this.artist = response.data.artist[0]
                    this.audio = response.data.songs
                    this.rutext = this.getRuTrans(response.data.text_ge)
                    this.rutitle = this.getRuTrans(response.data.title_ge)
                    this.entext = this.getEnTrans(response.data.text_ge)
                    this.entitle = this.getEnTrans(response.data.title_ge)
                    this.tabData = this.tabs(response.data)
                }

            })
            .catch(error => {
                console.log(error);
            })
    },
    computed: {
        ...mapGetters(['TEXTS', 'KARU', 'KAEN']),
        title() {
            let title = this.song.title_ru
            if (this.TEXTS.LOCALE === 'en') {
                title = this.song.title_en
            }
            if (this.TEXTS.LOCALE === 'ge') {
                title = this.song.title_ge
            }

            return title
        },
        artistName() {
            let artname = this.artist.title_ru
            if (this.TEXTS.LOCALE === 'en') {
                artname = this.artist.title_en
            }
            if (this.TEXTS.LOCALE === 'ge') {
                artname = this.artist.title_ge
            }
            let url = '/lyrics/' + this.artist.alias + '.html'
            return artname
        },


    },
    methods: {
        tabs(song) {
            let tabs = [
                this.TEXTS.CYR, this.TEXTS.LAT
            ]

            let tabItems = [
                '<h1>'+this.rutitle+'</h1>'+this.rutext,
                '<h1>'+this.entitle+'</h1>'+this.entext
            ]

            if (song.trans_ru.length > 0) {
                tabs.push(this.TEXTS.TRANS)
                tabItems.push(song.trans_ru)
            }
            if (song.trans_en.length > 0) {
                tabs.push(this.TEXTS.TRANS_EN)
                tabItems.push(song.trans_en)
            }
            if (song.chords.length > 0) {
                tabs.push(this.TEXTS.CHORDS)
                tabItems.push(song.chords)
            }
            return {tabs, tabItems}
        },
        getRuTrans(text) {
            return text.split("").map((letter) => {
                return (this.KARU[letter]) ? this.KARU[letter] : letter;
            }).join("");
        },
        getEnTrans(text) {
            return text.split("").map((letter) => {
                return (this.KAEN[letter]) ? this.KAEN[letter] : letter;
            }).join("");
        },
    }

}
</script>

<style scoped>
audio {
    width: 100%;
    height: 2rem;
}

.song_title {
    border-radius: .3rem
}
.lyrics{
    border:1px solid grey;
}
.lyrics_geo {
    max-width: 50%
}

.lyrics_tabs {
    width: 50%
}
.tabs_container{
    width: 100%;
}
.tabs{
    width: 50%;
}
.tab{
    padding: 2rem;
    text-transform: uppercase;

}
.tab_active{
    background-color: white;
}
</style>
