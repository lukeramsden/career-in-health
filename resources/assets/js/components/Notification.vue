<template>
  <a :href="route('notifications.click-through', {notification:model.id}) || 'javascript:'"
     class="link-unstyled">
    <template v-if="model.type === 'App\\Notifications\\ReceivedPrivateMessage'">
      <div :class="{ unread: model.read_at === null }"
           class="notification notification-private-message">
        <div class="notification-inner">
          <p>Message from <b>{{ model.data.sender_name }}</b></p>
          <p>{{ model.data.body.substr(0, 100) }}</p>
          <hr>
          <p><moments-ago :date="model.created_at" prefix="received" suffix="ago" /></p>
        </div>
      </div>
    </template>
    <template
      v-else-if="model.type
      === 'App\\Notifications\\CompanyReceivedListingApplication'">
      <div :class="{ unread: model.read_at === null }"
           class="notification notification-application">
        <div class="notification-inner">
          <p>Application from <b>{{ model.data.sender_name }}</b></p>
          <p v-if="model.data.body">{{ model.data.body.substr(0, 100) }}</p>
          <p v-else><span class="text-muted font-italic">No cover letter</span></p>
          <hr>
          <p><moments-ago :date="model.created_at" prefix="applied" suffix="ago" /></p>
        </div>
      </div>
    </template>
    <template v-else>
      <div :class="{ unread: model.read_at === null }"
           class="notification notification-unknown">
        <div class="notification-inner">
          <pre>{{ model.data }}</pre>
          <hr>
          <p><moments-ago :date="model.created_at" prefix="" suffix="ago" /></p>
        </div>
      </div>
    </template>
  </a>
</template>

<script>
export default {
  props: {
    model: {
      type: Object,
      required: true,
    },
  },
};
</script>
