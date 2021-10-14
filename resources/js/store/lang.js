const languages = require('../langs')
import axios from "axios"
export default ({
    state: {
        texts: languages,
        lang:'ru',
        karu: {},
        kaen: {},
        ruka:{},
        enka:{}
    },
    actions:{
        LOAD_LETTERS({commit}) {
        //    if(state.letters.length >0){
                axios
                    .get('/api/trans')
                    .then(response => {
                        commit('SET_TRANS', response.data)

                    })
                    .catch(error => {
                        console.log(error);
                        this.errored = true;
                    })
     //       }

        },
    },
    mutations: {
        SET_LANG: (state, lang) => {
            state.lang = lang
        },
        SET_LETTERS: (state, letters) => {
            state.letters = letters
        },
        SET_TRANS: (state,letters) =>{

            for (let key in letters){
                let ka = letters[key]['ka']
                let ru = letters[key]['ru']
                let en = letters[key]['en']
                state.karu[ka]=ru
                state.kaen[ka]=en
                state.ruka[ru]=ka
                state.enka[en]=ka
            }
        }
    },
    getters: {
        TEXTS(state) {
            return state.texts[state.lang]
        },
        KARU(state) {
            return state.karu
        },
        KAEN(state) {
            return state.kaen
        },
        RUKA(state) {
            return state.ruka
        },
        ENKA(state) {
            return state.enka
        },

    }
})
