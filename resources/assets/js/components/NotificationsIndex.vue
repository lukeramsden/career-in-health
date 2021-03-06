<template>
  <div class="container-fluid mt-4">
    <div id="notifications-index-row" class="row">
      <div class="col-12">
        <div class="row">
          <div class="col-12 col-lg-4 order-lg-last">
            <div class="position-sticky top-4">
              <div id="notification-filter-card"
                   class="card card-custom-material card-custom-material-hover">
                <div class="card-body p-0">
                  <input id="input-query" v-model="query"
                         class="input input-material w-100 p-3"
                         placeholder="Search" type="text">
                </div>
              </div>
              <div id="notification-actions-card"
                   class="card card-custom-material card-custom-material-hover
                   card-custom-no-top-bar mt-4">
                <div class="card-body p-0">
                  <div class="btn-group btn-group-full">
                    <button class="btn btn-primary" @click.stop.prevent="markAllAsRead">
                      Mark All As Read
                    </button>
                    <button class="btn btn-danger" @click.stop.prevent="deleteAllRead">
                      Delete All Read
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-lg-8">
            <div class="row">
              <div v-for="result of queryResults"
                   :key="result.id" class="col-12 col-lg-6">
                <notification :model="result" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
  data()
  {
    return {
      query: '',
    };
  },
  computed: {
    ...mapGetters( {
      notifications: 'notifications',
    } ),
    queryResults()
    {
      // Don't bother with scoring anything if the query is empty.
      if ( !this.query ) return this.notifications;

      // Preparing the query before-hand lets fuzzaldrin-plus optimize things a bit.
      const preparedQuery = fuzzaldrin.prepareQuery( this.query );

      // We use this to keep track of the similarity for each option.
      const scores = {};

      return [ ...this.notifications ]
        // Score each option & create a new array out of them.
        .map( ( notification ) =>
        {
          // See how well each field compares to the query.
          // Scores are a non-normalized number
          // representing how similar the query is to the field.
          const fieldScores = _
            .chain( this.dataKeys( notification ) )
            .map( k => notification.data[ k ] )
            .map( field => fuzzaldrin.score( field, this.query, { preparedQuery } ) )
            .value()
          ;

          // Store the highest score for this option
          // so we can compare it to other options.
          scores[ notification.id ] = Math.max( ...fieldScores );

          return notification;
        } )
        // Remove anything with a really low score.
        // You might want to play around with this.
        .filter( notification => scores[ notification.id ] > 1 )
        // Finally, sort by the highest score.
        .sort( ( a, b ) => scores[ b.id ] - scores[ a.id ] );
    },
  },
  mounted()
  {
    console.log( 'NotificationsIndex:mounted' );
  },
  methods: {
    dataKeys( n )
    {
      return _
        .chain( n.data )
        .omit( [ 'action' ] )
        .thru( v => Object.keys( v ) )
        .value();
    },
    titleCase( t )
    {
      return changeCase.titleCase( t );
    },
    markAllAsRead( event )
    {
      const $self = $( event.target );
      $self.prop( 'disabled', true );
      axios
        .post( route( 'notifications.mark-all-as-read' ) )
        .then( ( resp ) =>
        {
          if ( resp.data.success )
            this.$store.commit(
              'replaceNotifications',
              this.notifications.map( a => ( { ...a, read_at: moment() } ) ),
            );
        } )
        .catch( ( error ) =>
        {
          console.error( error );
          toastr.error( 'Could not mark notifications as read' );
        } )
        .then( () =>
        {
          $self.prop( 'disabled', false );
        } );
    },
    deleteAllRead( event )
    {
      const $self = $( event.target );
      $self.prop( 'disabled', true );
      axios
        .post( route( 'notifications.delete-all-read' ) )
        .then( ( resp ) =>
        {
          if ( resp.data.success )
            this.$store.commit(
              'replaceNotifications',
              this.notifications.filter( a => a.read_at == null ),
            );
        } )
        .catch( ( error ) =>
        {
          console.error( error );
          toastr.error( 'Could not delete read notifications' );
        } )
        .then( () =>
        {
          $self.prop( 'disabled', false );
        } );
    },
  },
};
</script>
