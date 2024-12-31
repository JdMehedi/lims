<template>
    <div v-html="paymentContent"></div>
</template>

<script>

export default {
    props: {
        paymentInfo: {
            default: {},
            type: Object,
            required: true
        }
    },
    data() {
        return {
            paymentContent: ''
        }
    },
    created() {

        if (typeof this.paymentInfo.unfixed_amount_object !== 'object' && this.paymentInfo.unfixed_amount_object == null) {
            this.$toast.error('Error: Unfixed amounts variable should be an object, ' + typeof this.paymentInfo.unfixed_amount_object + ' given');
        } else {
            this.getpaymentCreatePanel();
        }

    },
    methods: {
        getpaymentCreatePanel() {
            var app = this;

            axios.post('/spg/payment-panel', {
                app_id: app.paymentInfo.app_id,
                process_type_id: app.paymentInfo.process_type_id,
                payment_step_id: app.paymentInfo.payment_step_id,
                contact_name: app.paymentInfo.contact_name,
                contact_email: app.paymentInfo.contact_email,
                contact_phone: app.paymentInfo.contact_phone,
                contact_address: app.paymentInfo.contact_address,
                unfixed_amount_array: JSON.stringify(app.paymentInfo.unfixed_amount_object)
            }).then(response => {

                if (response.data.status === true) {
                    app.paymentContent = response.data.data.html;
                } else {
                    this.$toast.error(response.data.message);
                }


            }).catch(function (error) {
                // handle error
                console.log(error);
            })
                .then(function () {

                });
        }
    }
}
</script>

<style>

</style>