export default ({
    state: {
        artist: {},
        song: {}
    },
    mutations: {
        SET_ARTIST: (state, artist) => {
            state.artist = artist
        },
        SET_SONG: (state, song) => {
            state.song = song
        }
    },
    getters: {
        ARTIST(state) {
            return state.artist
        },
        SONG(state) {
            return state.song
        }

    }
})
