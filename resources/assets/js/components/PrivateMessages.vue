<template>
    <div class="private-message-widget card card-custom card-custom-material">
        <div class="card-body" v-chat-scroll="{always: false, smooth: true}">
            <div v-for="msg in messages" v-bind:key="msg.id" class="private-message-wrapper"
                 :class="determineSide(msg)">
                <div class="private-message-inner">
                    {{ msg.body }}
                </div>
                <p class="private-message-timestamp small">{{ formatTimestamp(msg) }}</p>
            </div>
        </div>
        <div class="card-footer p-0">
            <form v-on:submit.prevent="sendMessage" class="form-inline">
                <input type="hidden" name="job_listing_id" :value="listing_id">

                <input v-if="usertype === 'employee'"
                       type="hidden" name="to_company_id"
                       :value="company_id">

                <input v-else-if="usertype === 'company'"
                       type="hidden" name="to_employee_id"
                       :value="employee_id">

                <div class="input-group w-100" id="private-message-input-group">
                    <input
                        type="text"
                        class="form-control input-material"
                        name="body"
                        maxlength="1000"
                        placeholder="Hello!"
                        required/>
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-action px-3">Send</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                listing_id: data.privateMessages.listing_id,
                company_id: data.privateMessages.company_id,
                employee_id: data.privateMessages.employee_id,
                messages: data.privateMessages.messages,
                usertype: data.privateMessages.usertype,
            };
        },
        mounted() {
            this.$nextTick(() => {
                Echo.private(`App.PrivateMessage.Listing.${this.listing_id}.Employee.${this.employee_id}`)
                    .listen('CreatedPrivateMessage', (e) => {
                        this.renderMessage(e.message)
                            .then(msg => {
                                this.pushMessage(msg);
                            })
                            .catch(err => {
                                console.log(err);
                                toastr.error('Could not render message.');
                            })
                        ;
                    });

                this.sortMessages();
                $('#private-message-wrapper[data-toggle="tooltip"]').tooltip({
                    container: 'body',
                    placement: 'top',
                })
            });
        },
        computed: {},
        methods: {
            sortMessages() {
                this.messages.sort((a, b) => {
                    if (moment(a.created_at).isBefore(moment(b.created_at)))
                        return -1;

                    if (moment(a.created_at).isAfter(moment(b.created_at)))
                        return 1;

                    return 0;
                })
            },
            pushMessage(msg) {
                // push new message, ensure ID property is unique across the array
                // and then sort by created_at
                this.messages =
                    _.uniqBy(_.concat(this.messages, msg), 'id');

                this.sortMessages();
            },
            renderMessage(msg) {
                return new Promise((resolve, reject) => {
                    axios
                        .get(route('account.private-message.render', {message: msg.id}))
                        .then(res => {
                            msg.dom_template = res.data;
                            resolve(msg);
                        })
                        .catch(err => {
                            reject(err);
                        })
                        .then(() => {

                        })
                    ;
                });
            },
            sendMessage(e) {
                const $form = $(e.target);
                const $button = $form.find('button[type="submit"]');

                if ($button.prop('disabled'))
                    return;

                $button.prop('disabled', true);
                $form.find(':input').prop('readonly', true);
                axios
                    .post(route('account.private-message.store'), $form.serialize())
                    .then(res => {
                        if (res.data.success) {
                            this.renderMessage(res.data.model)
                                .then(msg => {
                                    this.pushMessage(msg);
                                })
                                .catch(err => {
                                    console.log(err);
                                    toastr.error('Could not render message.');
                                })
                                .then(() => {
                                    $form.trigger('reset');
                                    $form.find(':input').prop('readonly', false);
                                    $button.prop('disabled', false);
                                });
                        }
                    })
                    .catch(err => {
                        console.log(err);
                        toastr.error('Could not send message.');

                        // reset code is here instead of in final then because
                        // we only want it to run if request fails
                        // if request is a success
                        $form.find(':input').prop('readonly', false);
                        $button.prop('disabled', false);
                    })
                    .then(() => {
                    })
                ;
            },
            determineSide(msg) {
                // if direction
                return msg.direction ===
                // is opposite of usertype
                (this.usertype === 'employee' ? 'to_company' : 'to_employee')
                    ? 'right' : 'left';
            },
            formatTimestamp(msg) {
                return moment.utc(msg.created_at).local().format('lll');
            },
        }
    };
</script>
<style scoped lang="scss">
    @import '~@/_variables.scss';
    @import '~@/_mixins.scss';
    /*@import '~bootstrap/scss/variables';*/
    /*@import '~@material/elevation/mdc-elevation';*/

    @keyframes scaleIn {
        from {
            transform: scale(0);
        }
        to {
            transform: scale(1);
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