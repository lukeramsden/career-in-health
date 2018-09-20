export default {
    strict: process.env.NODE_ENV !== 'production',
    modules: {},
    state: {
        notifications: [],
        privateMessages: [],
        userType: '',
    },
    mutations: {
        newNotification:
            (state, payload) => state.privateMessages.push(payload),
        newPrivateMessage:
            (state, payload) => state.privateMessages.push(payload),
        updatePrivateMessage:
            (state, payload) => Object.assign(state.privateMessages.find(item => item.id === payload.id), payload),
        updateUserType:
            (state, payload) => state.userType = payload,
    },
    getters: {
        earliestUnreadMessage: state => {
            return _
                .chain(_.clone(state.privateMessages))
                .filter({
                    'read': 0,
                    'direction': state.userType === 'employee'
                        ? 'to_employee'
                        : 'to_company'
                })
                .map('id')
                .head()
                .value()
                ;
        },
        sortedPrivateMessages: state => {
            return _.clone(state.privateMessages).sort((a, b) => {
                if (moment(a.created_at).isBefore(moment(b.created_at)))
                    return -1;

                if (moment(a.created_at).isAfter(moment(b.created_at)))
                    return 1;

                return 0;
            });
        },
    },
};