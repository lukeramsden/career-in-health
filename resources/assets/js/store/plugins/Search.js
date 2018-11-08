export default {
  namespaced: true,
  state: {
    items: [],
  },
  mutations: {
    create:
      ( state, payload ) => state.items = _.isArray( payload )
        ? [ ...state.items, ...payload ]
        : [ ...state.items, payload ],
    update:
      ( state, payload ) => Object.assign( state.items.find(
        item => item.id === payload.id,
      ), payload ),
    replace:
      ( state, payload ) => state.items = payload,
    clear:
      ( state ) => state.items = [],
  },
  actions: {},
  getters: {},
};
