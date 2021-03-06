export default {
  namespaced: true,
  state: {
    messages: [],
  },
  mutations: {
    create:
      ( state, payload ) =>
      {
        if ( _.isArray( payload ) ) state.messages.push( ...payload );
        else state.messages.push( payload );
      },
    update:
      ( state, payload ) => Object.assign( state.messages.find(
        item => item.id === payload.id,
      ), payload ),
  },
  actions: {},
  getters: {
    earliestUnread: ( state, getters, rootState ) =>
    {
      if ( !rootState.userType ) return 0;

      if ( getters.sorted.length <= 0 ) return 0;

      return _
        .chain( _.clone( getters.sorted ) )
        .filter( {
          read: 0,
          direction: rootState.userType === 'employee'
            ? 'to_employee'
            : 'to_company',
        } )
        .map( 'id' )
        .head()
        .value();
    },
    sorted: ({ messages }) =>
      _.clone( messages || [] ).sort( ( a, b ) =>
      {
        if ( moment( a.created_at ).isBefore( moment( b.created_at ) ) ) return -1;

        if ( moment( a.created_at ).isAfter( moment( b.created_at ) ) ) return 1;

        return 0;
      } ),
    unreadCount: ( state ) =>
    {
      if ( !state.userType ) return 0;

      if ( state.messages.length <= 0 ) return 0;

      return _
        .chain( _.clone( state.messages ) )
        .filter( {
          read: 0,
          direction: state.userType === 'employee'
            ? 'to_employee'
            : 'to_company',
        } )
        .value()
        .length;
    },
  },
};
