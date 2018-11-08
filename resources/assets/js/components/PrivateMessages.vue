<template>
  <div class="private-message-widget card card-custom-material card-custom-material-hover">
    <div v-chat-scroll="{always: false, smooth: true}" class="card-body">
      <template v-if="loaded">
        <template v-if="messages.length > 0">
          <template v-for="msg in messages">
            <template v-if="msg.id === earliestUnreadMessage">
              <p class="unread-ruler small"
                 @click.stop.prevent="markMessagesAsRead">
                Unread Messages (click to mark as read)
              </p>
            </template>
            <div :key="msg.id" :class="determineSide(msg)"
                 class="private-message-wrapper">
              <div class="private-message-inner">
                {{ msg.body }}
              </div>
              <p class="private-message-timestamp small">{{ formatTimestamp(msg) }}</p>
            </div>
          </template>
        </template>
        <p v-else class="text-muted font-italic text-center my-0">No messages</p>
      </template>
      <loading-icon v-else/>
    </div>
    <div class="card-footer p-0">
      <form class="form-inline" @submit.prevent="sendMessage">
        <input :value="listing_id" type="hidden" name="job_listing_id">

        <input v-if="userType === 'employee'"
               :value="company_id" type="hidden"
               name="to_company_id">

        <input v-else-if="userType === 'company'"
               :value="employee_id" type="hidden"
               name="to_employee_id">

        <div id="private-message-input-group" class="input-group w-100">
          <input
            type="text"
            class="form-control input-material"
            name="body"
            maxlength="1000"
            placeholder="Hello!"
            required >
          <div class="input-group-append">
            <button type="submit" class="btn btn-action px-3">Send</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
/* global data */
import { mapGetters, mapState } from 'vuex';

export default {
  data()
  {
    return {
      listing_id: data.privateMessages.listing_id,
      employee_id: data.privateMessages.employee_id,
      company_id: data.privateMessages.company_id,
      loaded: false,
    };
  },
  computed: {
    ...mapState( {
      userType: 'userType',
    } ),
    ...mapGetters( 'PrivateMessagesModule', {
      earliestUnreadMessage: 'earliestUnread',
      messages: 'sorted',
    } ),
  },
  mounted()
  {
    axios
      .get( route( `account.private-message.show-${this.userType}`, {
        jobListing: this.listing_id,
        employee: this.employee_id,
      } ) )
      .then( res =>
      {
        if ( res.data.success )
          this.$store.commit( 'PrivateMessagesModule/create', res.data.models );
        else throw new Error( 'Could not load messages.' );
      } )
      .catch( e =>
      {
        console.error( e );
        toastr.error( 'Could not load messages.' );
      } )
      .then( () =>
      {
        this.loaded = true;
      } );

    this.$nextTick( () =>
    {
      Echo
        .private( `App.PrivateMessage.Listing.${this.listing_id}.Employee.${this.employee_id}` )
        .listen( 'CreatedPrivateMessage', e => this.pushMessage( e.message ) );
    } );
  },
  methods: {
    pushMessage( msg )
    {
      if ( _.findIndex( this.messages, [ 'id', msg.id ] ) )
        this.$store.commit( 'PrivateMessagesModule/create', msg );
    },
    markMessagesAsRead()
    {
      $( '.unread-ruler' ).addClass( 'scaleOut' );
      axios
        .post( route( 'account.private-message.mark-all-as-read', {
          jobListing: this.listing_id,
          employee: this.employee_id,
        } ) )
        .then( res =>
        {
          if ( res.data.success )
          {
            _
              .chain( _.clone( this.messages ) )
              .filter( {
                read: 0,
                direction: this.userType === 'employee'
                  ? 'to_employee'
                  : 'to_company',
              } )
              .forEach( ( el ) =>
              {
                const updatedMsg     = _.clone( el );
                updatedMsg.read    = 1;
                updatedMsg.read_at = res.data.read_at;
                this.$store.commit( 'PrivateMessagesModule/update', updatedMsg );
              } )
              .value()
            ;
          }
        } )
        .catch( err =>
        {
          console.log( err );
          $( '.unread-ruler' ).remove( 'scaleOut' );
        } )
        .then( () =>
        {
        } );
    },
    sendMessage( e )
    {
      const $form   = $( e.target );
      const $button = $form.find( 'button[type="submit"]' );

      if ( $button.prop( 'disabled' ) ) return;

      $button.prop( 'disabled', true );
      $form.find( ':input' ).prop( 'readonly', true );
      axios
        .post( route( 'account.private-message.store' ), $form.serialize() )
        .then( res =>
        {
          if ( res.data.success )
          {
            this.pushMessage( res.data.model );
            this.markMessagesAsRead();
          }
        } )
        .catch( err =>
        {
          console.log( err );
          toastr.error( 'Could not send message.' );
        } )
        .then( () =>
        {
          $form.trigger( 'reset' );
          $form.find( ':input' ).prop( 'readonly', false );
          $button.prop( 'disabled', false );
        } )
      ;
    },
    determineSide( msg )
    {
      // if direction
      return msg.direction
                // is opposite of userType
                === ( this.userType === 'employee' ? 'to_company' : 'to_employee' )
        ? 'right' : 'left';
    },
    formatTimestamp( msg )
    {
      return moment.utc( msg.created_at ).local().format( 'lll' );
    },
  },
};
</script>

<!--suppress CssUnknownTarget -->
<style scoped lang="scss">
    @import '~@/_variables.scss';
    @import '~@/_mixins.scss';

    @keyframes scaleIn {
        from {
            transform: scale(0);
        }
        to {
            transform: scale(1);
        }
    }

    @keyframes scaleOut {
        from {
            transform: scale(1);
        }
        to {
            transform: scale(0);
        }
    }

    @keyframes scaleXIn {
        from {
            transform: scaleX(0);
        }
        to {
            transform: scaleX(1);
        }
    }

    @keyframes scaleXOut {
        from {
            transform: scaleX(1);
        }
        to {
            transform: scaleX(0);
        }
    }

    @keyframes scaleYIn {
        from {
            transform: scaleY(0);
        }
        to {
            transform: scaleY(1);
        }
    }

    @keyframes scaleYOut {
        from {
            transform: scaleY(1);
        }
        to {
            transform: scaleY(0);
        }
    }

    .unread-ruler {
        // https://www.colourlovers.com/color/F02311/Sex_on_the_Floor
        $color: #F02311;
        text-align: center;
        border: 0;
        white-space: nowrap;
        display: block;
        overflow: hidden;
        padding: 0;
        margin: 0 1rem;
        color: $color;
        transition: transform 200ms ease-in-out;

        transform-origin: 0 0 0;
        animation: scaleXIn 0.4s ease-in-out;

        &.scaleOut {
            transform-origin: 50% 0 0;
            animation: scaleOut 0.4s ease-in-out;
        }

        // Opinionated: add "hand" cursor to non-disabled .btn elements
        &:not(:disabled):not(.disabled) {
            cursor: pointer;
        }

        & > * {
            display: inline-block;
            vertical-align: middle;
        }

        &:before, &:after {
            background-color: $color;
            content: "";
            height: 1px;
            width: 50%;
            margin: 0 5px 0 5px;
            display: inline-block;
            vertical-align: middle;
        }

        &:before {
            margin-left: -100%;
        }

        &:after {
            margin-right: -100%;
        }
    }

    .private-message {
        $pm-margin: 1rem;

        &-input-group {
            input, button {
                border-top-left-radius: 0;
                border-top-right-radius: 0;
            }
        }

        &-widget {
            .card-body {
                max-height: 600px;
                overflow-y: scroll;
            }
        }

        &-wrapper {
            animation: scaleIn 0.4s ease-in-out;
            margin: $pm-margin;

            &.left {
                margin-left: 0;
                margin-right: $pm-margin * 3;
                transform-origin: 0 50% 0;
            }

            &.right {
                margin-right: 0;
                margin-left: $pm-margin * 3;
                transform-origin: 100% 50% 0;
            }

            &.right + &.right,
            &.left + &.left {
                margin-top: -$pm-margin + 0.2rem;
            }

            &:first-child {
                margin-top: 0;
            }
        }

        &-inner {
            .left &:before,
            .right &:before,
            .left &:after,
            .right &:after {
                content: '';
                position: absolute;
                border-style: solid;
                width: 0;
                display: block;
                top: 50%;
                margin-top: -16px;
            }

            .left & {
                background-color: #fff;

                &:before {
                    border-width: 16px 16px 16px 0;
                    border-color: transparent;
                    z-index: 0;
                    left: -16px;

                }

                &:after {
                    border-width: 16px 16px 16px 0;
                    border-color: transparent #fff;
                    z-index: 1;
                    left: -15px;
                }
            }

            .right & {
                background-color: $action;
                color: #fff;

                &:before {
                    border-width: 16px 0 16px 16px;
                    border-color: transparent;
                    z-index: 0;
                    right: -16px;
                }

                &:after {
                    border-width: 16px 0 16px 16px;
                    border-color: transparent $action;
                    z-index: 1;
                    right: -16px;
                }
            }

            position: relative;
            padding: 0.75rem 1rem;
            border-radius: $border-radius;
        }

        &-timestamp {
            color: #6c757d;
            font-style: italic;
            margin: 5px 0;

            .left & {
                text-align: left;
            }

            .right & {
                text-align: right;
            }
        }
    }
</style>
