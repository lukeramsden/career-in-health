export default {
    strict: process.env.NODE_ENV !== 'production',
    plugins: [],
    modules: {},
    state: {
        notifications: [],
        privateMessages: [],
        userType: '',
        isOnboarding: false,
    },
    mutations: {
        updateUserType:
            (state, payload) => state.userType = payload,
        isOnboarding:
            (state, payload) => state.isOnboarding = true,
        isNotOnboarding:
            (state, payload) => state.isOnboarding = false,
        newNotification:
            (state, payload) => state.privateMessages.push(payload),
        newPrivateMessage:
            (state, payload) => state.privateMessages.push(payload),
        updatePrivateMessage:
            (state, payload) => Object.assign(state.privateMessages.find(item => item.id === payload.id), payload),
    },
    getters: {
        earliestUnreadMessage: state => {
            if(!state.userType)
                return 0;

            if(state.privateMessages.length <= 0)
                return 0;

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
            if(state.privateMessages.length <= 0)
                return [];

            return _.clone(state.privateMessages).sort((a, b) => {
                if (moment(a.created_at).isBefore(moment(b.created_at)))
                    return -1;

                if (moment(a.created_at).isAfter(moment(b.created_at)))
                    return 1;

                return 0;
            });
        },
        unreadMessageCount: state => {
            if(!state.userType)
                return 0;

            if(state.privateMessages.length <= 0)
                return 0;

            return _
                .chain(_.clone(state.privateMessages))
                .filter({
                    'read': 0,
                    'direction': state.userType === 'employee'
                        ? 'to_employee'
                        : 'to_company'
                })
                .value()
                .length
                ;
        },
    },
};