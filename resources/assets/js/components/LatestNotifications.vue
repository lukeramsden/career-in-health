<template>
  <div id="notification-panel">
    <div class="notification notification-actions">
      <a :href="route('notifications.index')" class="view-all-notifications">
        View All
      </a>
      <button :disabled="markAllAsReadLoading" class="mark-as-read" @click="markAllAsRead">
        Mark All As Read
      </button>
    </div>
    <notification v-for="item in latest" :model="item" :key="item.id" />
  </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
  data()
  {
    return {
      markAllAsReadLoading: false,
    };
  },
  computed: {
    ...mapGetters( {
      notifications: 'notifications',
    } ),
    latest()
    {
      return _.chain( _.clone( this.notifications ) ).sort( ( a, b ) =>
      {
        if ( a.read_at === null )
          return -1;

        if ( b.read_at === null )
          return 1;

        return 0;
      } ).take( 10 ).value();
    },
  },
  mounted()
  {
    console.log( 'LatestNotifications:mounted' );
  },
  methods: {
    async markAllAsRead()
    {
      this.markAllAsReadLoading = true;

      try
      {
        const response = await axios.post( route( 'notifications.mark-all-as-read' ) );

        if ( response.data.success )
          this.$store.commit( 'notificationsMarkAllAsRead' );
      }
      catch ( error )
      {
        console.log( error );
        toastr.error( 'Could not mark notifications as read' );
      }

      this.markAllAsReadLoading = false;
    },
  },
};
</script>
