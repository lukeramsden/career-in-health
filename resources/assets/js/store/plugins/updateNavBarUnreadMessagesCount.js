export default store => {
    // called when the store is initialized
    store.subscribe((mutation, state) => {
        // called after every mutation.
        // The mutation comes in the format of `{ type, payload }`.
        console.log(mutation);
        if (mutation.type === 'newPrivateMessage' ||
            mutation.type === 'updatePrivateMessage') {
            let elements = $('.unread-messages-count-badge');
            if (elements.length > 0) {
                elements.each((idx, el) => {
                    const $el = $(el);
                    $el.removeClass('bounce');

                    /**
                     * Kinda works
                     *
                     *      - Need to change the badge to always show
                     *      - Need to count unread messages from other threads
                     */

                    setTimeout(() => {
                        $el.addClass('bounce');
                        $el.html(store.getters.unreadMessageCount);
                    }, 0);
                });
            }
        }
    })
};