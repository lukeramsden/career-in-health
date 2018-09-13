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
    </div>
</template>

<script>
    export default {
        data() {
            return {
                listing_id: data.smallPrivateMessages.listing_id,
                company_id: data.smallPrivateMessages.company_id,
                employee_id: data.smallPrivateMessages.employee_id,
                messages: data.smallPrivateMessages.messages,
                usertype: data.smallPrivateMessages.usertype,
            };
        },
        mounted() {
            this.$nextTick(() => {
                Echo.private(`App.PrivateMessage.Listing.${this.listing_id}.Employee.${this.employee_id}`)
                    .listen('CreatedPrivateMessage', (e) => {
                        this.messages.push(e.message);
                    });
            });
        },
        methods: {
            sendMessage(e) {
                const $form = $(e.target);
                const $button = $(e.explicitOriginalTarget);

                $button.prop('disabled', true);
                axios
                    .post(route('account.private-message.store'), $form.serialize())
                    .then(res => {
                        if (res.data.success) {
                            this.messages.push(res.data.model);
                            $form.trigger('reset');
                        }
                    })
                    .catch(err => {
                        console.log(err);
                        toastr.error('Could not send message.')
                    })
                    .then(() => {
                        $button.prop('disabled', false);
                    })
                ;
            }
        }
    };
</script>