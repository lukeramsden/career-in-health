<template>
    <div>
        <div class="card card-custom mb-4" id="new-message">
            <form v-on:submit.prevent="sendMessage">
                <div class="card-body">
                    <input type="hidden" name="job_listing_id" :value="listing_id">

                    <input v-if="usertype === 'employee'"
                           type="hidden" name="to_company_id"
                           :value="company_id">

                    <input v-else-if="usertype === 'company'"
                           type="hidden" name="to_employee_id"
                           :value="employee_id">

                    <textarea
                        class="form-control"
                        name="body"
                        rows="3"
                        maxlength="1000"
                        required></textarea>
                </div>
                <div class="card-footer p-0">
                    <button type="submit" class="btn btn-primary btn-block">Send</button>
                </div>
            </form>
        </div>
        <template v-for="msg in messages">
            <template v-if="msg.dom_template">
                <div v-html="msg.dom_template"></div>
            </template>
            <template v-else>
                <p>Error rendering message. Please refresh the page.</p>
            </template>
        </template>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                listing_id:  data.smallPrivateMessages.listing_id,
                company_id:  data.smallPrivateMessages.company_id,
                employee_id: data.smallPrivateMessages.employee_id,
                messages:    data.smallPrivateMessages.messages,
                usertype:    data.smallPrivateMessages.usertype,
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
            });
        },
        methods: {
            pushMessage(msg) {
                this.messages.unshift(msg);
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
                const $button = $(e.explicitOriginalTarget);

                $button.prop('disabled', true);
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
                                    $button.prop('disabled', false);
                                });
                        }
                    })
                    .catch(err => {
                        console.log(err);
                        toastr.error('Could not send message.');
                        $button.prop('disabled', false);
                    })
                    .then(() => {

                    })
                ;
            }
        }
    };
</script>