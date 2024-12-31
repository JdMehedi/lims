<template>

    <div v-if="errorMsg" class="alert alert-danger" role="alert">{{ errorMsg }}</div>

    <div class="row" v-if="isLoading">
        <div class="col align-self-center">
            <div class="alert" :class="isLoadingAlertClass" id="loadingAlert" role="alert"><i
                    class="fa fa-spinner fa-pulse"></i> {{
                    isLoadingMsg
                    }}</div>
        </div>
    </div>

</template>

<script>
export default {
    data() {
        return {
            isLoadingAlertClass: 'alert-danger',
            isLoading: false,
            isLoadingMsg: 'Please wait...',
            isLoadingStartTime: '',
            isLoadingInterVal: '',
            errorMsg: '',
        }
    },
    mounted: function () {
        this.isLoading = true;
        this.isLoadingStartTime = new Date();
        this.isLoadingInterVal = window.setInterval(() => {
            this.appLoadingTimeCount()
        }, 1000);
    },
    methods: {
        appLoadingTimeCount() {

            // Difference between Start time and now is the time
            if (this.checkTimeDif() > 10) {
                this.isLoadingMsg = 'Opening form...';
                this.isLoadingAlertClass = 'alert-success';
            } else if (this.checkTimeDif() > 6) {
                this.isLoadingMsg = 'It is almost done...';
                this.isLoadingAlertClass = 'alert-info';
            } else if (this.checkTimeDif() > 2) {
                this.isLoadingMsg = 'Preparing all data...';
                this.isLoadingAlertClass = 'alert-warning';
            } else {
                this.isLoadingMsg = 'Please wait...';
                this.isLoadingAlertClass = 'alert-danger';
            }
        },
        checkTimeDif() {
            const now_is_the_time = new Date();
            return Math.floor((now_is_the_time - this.isLoadingStartTime) / 1000);
        },
        stopAppLoading() {
            this.isLoading = false;
            window.clearInterval(app.isLoadingInterVal);
        },
        setErrorMessage(msg) {
            if (msg) {
                this.errorMsg = msg;
            }
        }
    }
}
</script>

<style>
#loadingAlert {
    max-width: 200px;
    margin: 0 auto;
}
</style>