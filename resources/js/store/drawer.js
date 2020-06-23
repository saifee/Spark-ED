export default {
  namespaced: true,
  state: {
    navigationDrawer: true
  },
  mutations: {
    toggleNavigationDrawer (state) {
      state.navigationDrawer = !state.navigationDrawer
    }
  },
  actions: {},
  getters: {}
}
