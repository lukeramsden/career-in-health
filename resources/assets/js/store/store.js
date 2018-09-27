import PrivateMessagesModule from './plugins/PrivateMessages';

export default {
    strict: process.env.NODE_ENV !== 'production',
    plugins: [],
    modules: {
        privateMessages: PrivateMessagesModule,
    },
    state: {
        userType: '',
    },
    mutations: {
        updateUserType:
            (state, payload) => state.userType = payload,
    },
    getters: {},
};