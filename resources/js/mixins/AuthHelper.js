export default {
    methods: {
        Auth() {
            return this.$store.getters.getUser
            // console.log(window.user);
            // delete window.user.password;
            // delete window.user.user_hash;
            // delete window.user.login_token;
            // return window.user;
        },
        IsLoggedIn() {
            return this.$store.getters.getIsLoggedIn
        },
        getUserDeskIds() {
            if (this.Auth()) {
                return this.Auth().desk_id.split(',').map(Number);
            } else {
                return [];
            }
        },
        getUserFullName() {
            if (this.Auth()) {
                return [this.Auth().user_first_name, this.Auth().user_middle_name, this.Auth().user_last_name].join(' ');
            } else {
                return 'N/A';
            }
        }
    },
}