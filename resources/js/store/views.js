export default ({
    state: {
        view: 'grid'
    },
    mutations: {
        SET_VIEW: (state, view) => {
            state.view = view
        }
    },
    getters: {
        VIEW(state) {
            return state.view
        }
    }
})
