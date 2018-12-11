<template>
  <div>
    <div v-if="loaded" id="notification-panel">
      <div class="notification notification-actions">
        <a :href="route('notifications.index')" class="view-all-notifications">
          View All
        </a>
        <button class="mark-as-read">Mark All As Read</button>
      </div>
      <notification v-for="item in latest" :model="item" :key="item.id" />
    </div>
    <loading-icon v-else />
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import Notification   from './Notification.vue';

export default {
  components: {
    Notification,
  },
  data()
  {
    return {
      loaded: false,
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
          return 1;

        if ( b.read_at === null )
          return -1;

        return 0;
      } ).take( 10 ).value();
    },
  },
};
</script>
