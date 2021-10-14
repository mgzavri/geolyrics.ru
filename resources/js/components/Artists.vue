<template>
    <div class="artists">
        <h1 class="mb-5 mt-5" v-text="TEXTS.ARTISTS"></h1>


        <v-text-field v-model="searchString" :label="TEXTS.SEARCH_ARTIST"></v-text-field>

        <div class="artists__control d-flex justify-content-between">
            <div class="sort d-flex justify-content-left" v-if="!$vuetify.breakpoint.mobile">
                <div class="sort__item col-md-auto " id="byId"><span id="sortByDate" class="active"
                                                                     @click="sortedArtists('sortByDate',dir)"
                                                                     v-text="TEXTS.SORT_BY_ID"></span></div>
                <div class="sort__item col-md-auto" id="byName"><span id="sortByTitle"
                                                                      @click="sortedArtists('sortByTitle',dir)"
                                                                      v-text="TEXTS.SORT_BY_NAME"></span></div>
                <div class="sort__item col-md-auto" id="byNum"><span id="sortByNum"
                                                                     @click="sortedArtists('sortByNum',dir)"
                                                                     v-text="TEXTS.SORT_BY_NUM"></span></div>
                <div class="sort__item col-md-auto" id="byRate"><span id="sortByRate"
                                                                      @click="sortedArtists('sortByRate',dir)"
                                                                      v-text="TEXTS.SORT_BY_RATE"></span></div>

            </div>
            <div v-if="$vuetify.breakpoint.mobile">
                <v-menu offset-y>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                            color="primary"
                            dark
                            v-bind="attrs"
                            v-on="on"
                        >
                            {{ TEXTS.SORTING }}
                        </v-btn>
                    </template>
                    <v-list>
                        <v-list-item
                            v-for="(item, index) in items"
                            :key="index"
                        >
                            <v-list-item-title @click="sortedArtists('{{item.sort}}',dir)">{{
                                    item.title
                                }}
                            </v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-menu>

            </div>

            <div class="views d-flex justify-end ">
                <span class="material-icons h2" @click="SET_VIEW('grid')">apps</span>
                <span class="material-icons h2" @click="SET_VIEW('list')">line_style</span>
            </div>
        </div>


        <div v-if="VIEW==='list'" class="d-flex flex-wrap justify-content-center m-2">
            <div v-for="(isp) in filteredArtists" :id="isp.id" class="artist__item">
                <router-link :to="'/lyrics/'+isp.alias+'.html'">
                            <span v-text="getTitle(isp)" :style="{fontSize:getSize(isp) + 'px'}"></span>
                </router-link>
            </div>

        </div>


        <div v-if="VIEW==='grid'" class="d-flex justify-content-between flex-wrap ">

            <div class="m-auto" v-for="(isp) in filteredArtists" :id="isp.id">

                <router-link :to="'/lyrics/'+isp.alias+'.html'">
                    <div class="artist__block" :class="isp.class">

                        <div class="artist__image"
                             :style="{ backgroundImage: 'url(/storage/images/'+isp.photo+')'}"></div>

                        <div class="artist__title">
                            <div v-text="getTitle(isp)"></div>
                            <div v-text="isp.num+' '+getNoun(isp.num)"></div>
                        </div>


                    </div>

                </router-link>


            </div>


        </div>

    </div>


</template>

<script>
import {mapGetters, mapMutations} from 'vuex'

export default {
    name: "Artists",
    data: function () {
        return {
            searchString: "",
            artists: [],
            lyrics: [],
            loading: false,
            dir: 'up',
            maxHits:0,
            minHits:0

        };

    },
    mounted() {
        axios
            .get('/api/artists')
            .then(response => {
                if (response.data.status === 'false') {
                    return;

                } else {
                    this.loading = false
                    this.artists = response.data
                    let hits = response.data.map(artist=>artist.hits)
                    this.maxHits = Math.max.apply(null, hits)
                    this.minHits = Math.min.apply(null, hits)
                }

            })
            .catch(error => {
                console.log(error);
            })
    },
    computed: {
        ...mapGetters(['TEXTS', 'LANGS', 'VIEW']),
        filteredArtists() {
            let search = this.searchString;
            const filtered = this.artists.filter(function (artist) {
                return (artist.title_ru.toLowerCase().indexOf(search.toLowerCase()) > -1)
                    || (artist.title_en.toLowerCase().indexOf(search.toLowerCase()) > -1)
                    || (artist.title_ge.toLowerCase().indexOf(search.toLowerCase()) > -1);
            })

            return filtered;
        },
        items(){
           return [
            {title: this.TEXTS.SORT_BY_ID, sort: 'sortByDate'},
            {title: this.TEXTS.SORT_BY_NAME, sort: 'sortByName'},
            {title: this.TEXTS.SORT_BY_NUM, sort: 'sortByNum'},
            {title: this.TEXTS.SORT_BY_RATE, sort: 'sortByRate'},
        ]},

    },
    methods: {
        ...mapMutations(['SET_VIEW']),
        getNoun(number) {
            let n = Math.abs(number)
            if (this.TEXTS.LOCALE === 'ru') {

                n %= 100;
                if (n >= 5 && n <= 20) {
                    return 'песен'
                }
                n %= 10;
                if (n === 1) {
                    return 'песня'
                }
                if (n >= 2 && n <= 4) {
                    return 'песни'
                }
                return 'песен'

            }
            if (this.TEXTS.LOCALE === 'en') {
                if (n > 1) return 'songs'
                return 'song'
            }
            if (this.TEXTS.LOCALE === 'ge') {
                return 'სიმღერა'
            }


        },
        getSize(isp){
            let weight = (this.maxHits>0) ? Math.round(isp.hits/(this.maxHits-this.minHits)*100)/10:0
            let rate = (isp.raters>0) ? Math.round(isp.rate_points / isp.raters) : 0
            return weight+rate+14
        },
        getTitle(isp) {
            if (this.TEXTS.LOCALE === 'ru') {
                return isp.title_ru
            }
            if (this.TEXTS.LOCALE === 'en') {
                return isp.title_en
            }
            if (this.TEXTS.LOCALE === 'ge') {
                return isp.title_ge
            }
        },


        sortedArtists(type = 'sortByDate', dir) {
            let locale = this.TEXTS.LOCALE
            const els = document.querySelectorAll('.sort__item span')
            els.forEach(el => el.classList.remove("active"))


            let objID = ''
            switch (type) {

                case 'sortByTitle':
                    objID = document.querySelector('#sortByTitle');
                    objID.classList.add("active")
                    if (dir === 'up') {
                        this.artists.sort(function (a, b) {
                            if (locale === 'en') {
                                return a.title_en.toLowerCase().localeCompare(b.title_en.toLowerCase());
                            }
                            if (locale === 'ka') {
                                return a.title_ge.toLowerCase().localeCompare(b.title_ge.toLowerCase());
                            }
                            return a.title_ru.toLowerCase().localeCompare(b.title_ru.toLowerCase());
                        });
                        this.dir = 'down';
                    } else {
                        this.artists.sort(function (a, b) {
                            if (locale === 'en') {
                                return b.title_en.toLowerCase().localeCompare(a.title_en.toLowerCase());
                            }
                            if (locale === 'ka') {
                                return b.title_ge.toLowerCase().localeCompare(a.title_ge.toLowerCase());
                            }
                            return b.title_ru.toLowerCase().localeCompare(a.title_ru.toLowerCase());
                        });
                        this.dir = 'up';
                    }

                    break;

                case 'sortByDate':
                    objID = document.querySelector('#sortByDate');
                    objID.classList.add("active")
                    if (dir === 'up') {
                        this.artists.sort(function (a, b) {
                            return ((b.id - a.id) > 0) ? 1 : -1;
                        });
                        this.dir = 'down';
                    } else {
                        this.artists.sort(function (a, b) {
                            return ((b.id - a.id) > 0) ? -1 : 1;
                        });
                        this.dir = 'up';
                    }

                    break;

                case 'sortByNum':
                    objID = document.querySelector('#sortByNum');
                    objID.classList.add("active")
                    if (dir === 'up') {
                        this.artists.sort(function (a, b) {
                            return ((b.num - a.num) > 0) ? 1 : -1;
                        });
                        this.dir = 'down';
                    } else {
                        this.artists.sort(function (a, b) {
                            return ((b.num - a.num) > 0) ? -1 : 1;
                        });
                        this.dir = 'up';
                    }

                    break;
                    case 'sortByRate':
                    objID = document.querySelector('#sortByRate');
                    objID.classList.add("active")
                    if (dir === 'up') {
                        this.artists.sort(function (a, b) {
                            if ((b.rate_points/b.raters - a.rate_points/a.raters) > 0){
                                return 1
                            }
                            if ((b.hits - a.hits) > 0){
                                return 1
                            }else{
                                return -1
                            }


                        });

                        this.dir = 'down';
                    } else {
                        this.artists.sort(function (a, b) {
                            if ((b.rate_points/b.raters - a.rate_points/a.raters) > 0){
                                return -1
                            }
                            if ((b.hits - a.hits) > 0){
                                return -1
                            }else{
                                return 1
                            }


                        });

                        this.dir = 'up';
                    }

                    break;

                default:
                    if (dir === 'up') {
                        this.artists.sort(function (a, b) {
                            return ((b.num - a.num) > 0) ? 1 : -1;
                        });
                        this.dir = 'down';
                    } else {
                        this.artists.sort(function (a, b) {
                            return ((b.num - a.num) > 0) ? -1 : 1;
                        });
                        this.dir = 'up';
                    }

                    break;
            }

        }

    }
}
</script>
<style lang="scss" scoped>
a {
    text-decoration: none !important;
}

.artists {
    max-width: 100%;
    overflow: hidden;


}

.views span {
    cursor: pointer;
}

.active {
    color: #e8112d
}

.sort {
    display: flex;
    justify-content: left;
    align-items: flex-end;
    border-bottom: 2px solid #ddd;


    &__title {
        text-transform: uppercase;
        color: #444;
        padding-right: 20px;
    }

    &__item {
        text-transform: uppercase;
        color: #888;
        text-align: left;
        padding-right: 20px;
        cursor: pointer
    }

    &__item:hover {
        color: #fc1515
    }
}

.artist {
    &__block {
        border: medium solid #efefef;
        width: auto;
        height: 230px;
        margin: 10px;
        display: inline-block;
        vertical-align: top;
        padding: 5px;
        position: relative
    }

    &__block:hover {
        cursor: pointer;
        border: medium solid #fbd034;
        padding: 5px;
        display: block;
    }

    &__image {
        width: 150px;
        height: 150px;
        margin: 0 auto;
        border-radius: 3px;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: top center;
    }

    &__min_image {
        width: 50px;
        height: 50px;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: top center;
        margin-right: 1rem;
    }

    &__title {
        text-align: left;
        color: #444;
        line-height: 1.5;
        padding: 5px 0px 0px 5px
    }

    &__line {
        border-radius: .5rem;
        text-transform: uppercase;
    }

    &__container {
        margin: 1rem 0;
        width: 400px;
    }

    &__container:hover {
        background-color: #eaeaea;
    }
    &__item{
        margin:20px;
        a span{
            color:#455a64
        }
    }

}


</style>
