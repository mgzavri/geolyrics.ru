<template>
    <div class="container">

        <div class="artist">
            <div class="artist__details d-flex justify-content-between flex-wrap">

                <h1 class="primary text-white w-100" v-if="$vuetify.breakpoint.mobile">{{ title }}</h1>

                <div class="artist__photos " :class="$vuetify.breakpoint.mobile? 'w-100':'w-50'">
                    <v-carousel

                        :height="photoHeight"

                        hide-delimiter-background>
                        <v-carousel-item
                            v-for="(item,i) in artist.photos"
                            :key="i"
                            :src="'/storage/images/'+item.path"
                            reverse-transition="fade-transition"
                            transition="fade-transition"

                        ></v-carousel-item>
                    </v-carousel>
                </div>
                <div class="artist__description" v-if="!$vuetify.breakpoint.mobile">
                    <h1 class="primary text-white ">{{ title }}</h1>
                    <div class="rating">


                        <v-rating
                            v-model="rating"
                            color="primary"
                            background-color="primary"
                            hover
                            @input="updateRating($event)"
                        ></v-rating>

                    </div>
                    <div v-html="desc"></div>
                </div>

            </div>

                <v-data-table v-if="!$vuetify.breakpoint.mobile"
                              :headers="tableHeaders"
                              :items="tableSongs"
                              :items-per-page="20"
                              :item-class= "row_class"
                              class="elevation-1 text-capitalize"
                              @click:row="clicked"
                >

                    <template v-slot:item.trans_ru="{ item }">
                        <v-icon light :color="(item.trans_ru>0) ? 'red':'grey'"> {{ existIcon(item.trans_ru) }}</v-icon>
                    </template>
                    <template v-slot:item.trans_en="{ item }">
                        <v-icon light :color="(item.trans_en>0) ? 'red':'grey'"> {{ existIcon(item.trans_en) }}</v-icon>
                    </template>
                    <template v-slot:item.chords="{ item }">
                        <v-icon light :color="(item.chords>0) ? 'red':'grey'"> {{ existIcon(item.chords) }}</v-icon>
                    </template>


                </v-data-table>

            <v-simple-table v-if="$vuetify.breakpoint.mobile">
                <template v-slot:default>
                    <thead>
                    <tr>
                        <th class="text-left  blue-grey--text text--darken-2 text-uppercase text-center">
                            {{ TEXTS.SONGS }}
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr
                        v-for="song in lyrics"
                        :key="song.alias"
                    >
                        <td class="song">
                            <router-link :to="'/lyrics/'+artist.alias+'/'+song.alias+'.html'">{{ getSongTitle(song) }}</router-link>
                        </td>
                    </tr>
                    </tbody>
                </template>
            </v-simple-table>


        </div>
    </div>
</template>

<script>
import {mapGetters, mapMutations} from 'vuex'

export default {
    name: "Artist",
    data() {
        return {
            artist: {},
            lyrics: [],
            searchString: "",
            loading: true,
            rating: 0
        }

    },

    mounted() {
        console.log(this.$route.params.artist)
        let alias = this.$route.params.artist.split('.')[0]
        this.getArtistData(alias)
    },

    computed: {
        ...mapGetters(['TEXTS', 'KARU', 'KAEN']),
        title() {
            let title = this.artist.title_ru
            if (this.TEXTS.LOCALE === 'en') {
                title = this.artist.title_en
            }
            if (this.TEXTS.LOCALE === 'ge') {
                title = this.artist.title_ge
            }

            return title
        },
        desc() {
            let desc = this.artist.desc_ru
            if (this.TEXTS.LOCALE === 'en') {
                desc = this.artist.desc_en
            }
            if (this.TEXTS.LOCALE === 'ge') {
                desc = this.artist.desc_ge
            }

            return desc
        },

        tableHeaders() {
            let headers = []
            switch (this.TEXTS.LOCALE) {
                case 'en':
                    headers = [
                        {text: 'Title', align: 'start', sortable: true, value: 'title'},
                        {text: 'Georgian', value: 'title_ge'},
                        {text: 'Transcription', value: 'transcr_en'},
                        {text: 'Rus', value: 'trans_ru'},
                        {text: 'Eng', value: 'trans_en'},
                        {text: 'Chords', value: 'chords'},
                    ]
                    break
                case 'ru':
                    headers = [
                        {text: 'Название', align: 'start', sortable: true, value: 'title'},
                        {text: 'სახელი', value: 'title_ge'},
                        {text: 'Транскрипция', value: 'transcr_ru'},
                        {text: 'Перевод', value: 'trans_ru'},
                        {text: 'English', value: 'trans_en'},
                        {text: 'Аккорды', value: 'chords'},
                    ]
                    break
                case 'ge':
                    headers = [
                        {text: 'სახელი', align: 'start', sortable: true, value: 'title'},
                        {text: 'რუსულად', value: 'title_ru'},
                        {text: 'რუს', value: 'trans_ru'},
                        {text: 'ინგ', value: 'trans_en'},
                        {text: 'აკორდები', value: 'chords'},
                    ]
                    break
                default:
                    headers = [
                        {text: 'Название', align: 'start', sortable: true, value: 'title'},
                        {text: 'სახელი', value: 'title_ge'},
                        {text: 'Транскрипция', value: 'transcr_ru'},
                        {text: 'Перевод', value: 'trans_ru'},
                        {text: 'English', value: 'trans_en'},
                        {text: 'Аккорды', value: 'chords'},
                    ]


            }

            return headers
        },
        tableSongs() {
            let songs = []

            switch (this.TEXTS.LOCALE) {
                case 'en':
                    this.lyrics.forEach(item => {
                        songs.push({
                            title: item.title_en,
                            title_ge: item.title_ge,
                            transcr_en: this.getEnTrans(item.title_ge),
                            trans_ru: (item.trans_ru) ? 1 : 0,
                            trans_en: (item.trans_en) ? 1 : 0,
                            chords: (item.chords) ? 1 : 0,
                            alias:item.alias
                        })

                    })
                    break
                case 'ru':
                    this.lyrics.forEach(item => {
                        songs.push({
                            title: item.title_ru,
                            title_ge: item.title_ge,
                            transcr_ru: this.getRuTrans(item.title_ge),
                            trans_ru: (item.trans_ru) ? 1 : 0,
                            trans_en: (item.trans_en) ? 1 : 0,
                            chords: (item.chords) ? 1 : 0,
                            alias:item.alias
                        })

                    })

                    break
                case 'ge':
                    this.lyrics.forEach(item => {
                        songs.push({
                            title: item.title_ge,
                            title_ru: item.title_ru,
                            trans_ru: (item.trans_ru) ? 1 : 0,
                            trans_en: (item.trans_en) ? 1 : 0,
                            chords: (item.chords) ? 1 : 0,
                            alias:item.alias
                        })
                    })


                    break
                default:
                    this.lyrics.forEach(item => {
                        songs.push({
                            title: item.title_en,
                            title_ge: item.title_ge,
                            transcr_en: this.getEnTrans(item.title_ge),
                            trans_ru: (item.trans_ru) ? 1 : 0,
                            trans_en: (item.trans_en) ? 1 : 0,
                            chords: (item.chords) ? 1 : 0,
                            alias:item.alias
                        })

                    })

            }

            return songs
        },
        photoHeight() {
            return this.$vuetify.breakpoint.mobile ? 200 : 400
        }

    },

    methods: {
        ...mapMutations(['SET_ARTIST']),
        async getArtistData(alias) {
            await axios
                .get('/api/artists/' + alias)
                .then(response => {
                    if (response.data.status === 'false') {
                        this.$router.push('/404')

                    } else {
                        this.loading = false
                        let data = response.data
                        this.artist = data
                        this.lyrics = data.lyrics
                        this.rating = (data.raters > 0) ? Math.round(data.rate_points / data.raters) : 0
                        this.SET_ARTIST(data)
                    }

                })
                .catch(error => {
                    console.log(error)
                    this.errored = true
                    this.artist = 'empty'
                    this.$router.push('/404')
                })
        },
        clicked(value){
           this.$router.push('/lyrics/'+this.artist.alias+'/'+value.alias+'.html')
        },
        getSongTitle(song) {
            let title = song.title_ru
            if (this.TEXTS.LOCALE === 'en') {
                title = song.title_en
            }
            if (this.TEXTS.LOCALE === 'ge') {
                title = song.title_ge
            }

            return title
        },
        existIcon(exist) {
            return (exist > 0) ? 'done' : 'highlight_off'
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
        updateRating(e) {
            axios
                .patch('/api/artists/rate', {
                    id: this.artist.id,
                    rate: e
                })
                .catch(error => {
                    console.log(error)
                })
        },
        row_class(item) {
                return "songline"
        }


    }

}
</script>

<style lang="scss">

.songline{
    cursor: pointer;
}
.artist {

    &__photos {
        border-radius: 0.3rem;
        overflow: hidden;
    }

    &__description {
        width: 50%;
        padding: 0 1rem;
        overflow-y: auto;
        max-height: 400px;
        h1 {
            padding: 0.2rem 0.2rem 0.2rem 1rem;
            border-radius: 0.3rem;
            font-size: 2rem
        }

    }
}


</style>
