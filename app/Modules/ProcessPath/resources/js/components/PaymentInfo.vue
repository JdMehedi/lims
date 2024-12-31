<template>
    <div class="card">
        <div>
            <h5 class="card-header bg-success"><strong>Payment Information</strong></h5>
        </div>
        <div class="card-body payment-info">

            <div class="card" v-for="(payment, key) in payment_info" :key="key">
                <div>
                    <h5 class="card-header bg-info"><b>{{ payment.payment_name }}</b></h5>
                </div>
                <div class="card-body">
                    <div class="row form-group">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-5">
                                    <span class="v_label">Contact name</span>
                                    <span class="float-right">&#58;</span>
                                </div>
                                <div class="col-md-7">
                                    {{ payment.contact_name }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-5">
                                    <span class="v_label">Contact email</span>
                                    <span class="float-right">&#58;</span>
                                </div>
                                <div class="col-md-7">
                                    {{ payment.contact_email }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-5">
                                    <span class="v_label">Contact phone</span>
                                    <span class="float-right">&#58;</span>
                                </div>
                                <div class="col-md-7">
                                    {{ payment.contact_no }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-5">
                                    <span class="v_label">Contact address</span>
                                    <span class="float-right">&#58;</span>
                                </div>
                                <div class="col-md-7">
                                    {{ payment.address }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-5">
                                    <span class="v_label">Pay amount</span>
                                    <span class="float-right">&#58;</span>
                                </div>
                                <div class="col-md-7">
                                    {{ payment.pay_amount }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-5">
                                    <span class="v_label">VAT on pay amount</span>
                                    <span class="float-right">&#58;</span>
                                </div>
                                <div class="col-md-7">
                                    {{ payment.vat_on_pay_amount }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-5">
                                    <span class="v_label">Transaction charge</span>
                                    <span class="float-right">&#58;</span>
                                </div>
                                <div class="col-md-7">
                                    {{ payment.transaction_charge_amount }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-5">
                                    <span class="v_label">VAT on transaction charge</span>
                                    <span class="float-right">&#58;</span>
                                </div>
                                <div class="col-md-7">
                                    {{ payment.vat_on_transaction_charge }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-5">
                                    <span class="v_label">Total Amount</span>
                                    <span class="float-right">&#58;</span>
                                </div>
                                <div class="col-md-7">
                                    {{ payment.total_amount }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-5">
                                    <span class="v_label">Payment status</span>
                                    <span class="float-right">&#58;</span>
                                </div>
                                <div class="col-md-7">

                                    <span v-if="payment.payment_status == 0" class="label label-warning">Pending</span>

                                    <span v-else-if="payment.payment_status == '-1'"
                                        class="label label-info">In-progress</span>

                                    <span v-else-if="payment.payment_status == 1"
                                        class="label label-success">Paid</span>

                                    <span v-else-if="payment.payment_status == 2"
                                        class="label label-danger">Exception</span>

                                    <span v-else-if="payment.payment_status == 3" class="label label-warning">Waiting
                                        for payment confirmation</span>

                                    <span v-else-if="payment.payment_status == '-3'" class="label label-warning">Payment
                                        cancelled</span>

                                    <span v-else class="label label-warning">Invalid status</span>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-5">
                                    <span class="v_label">Payment mode</span>
                                    <span class="float-right">&#58;</span>
                                </div>
                                <div class="col-md-7">
                                    {{ payment.pay_mode }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-md-12">
                            <a class="btn btn-default btn-block" data-toggle="collapse"
                                :href="'#multiCollapseExample' + payment.id" role="button" aria-expanded="false"
                                aria-controls="multiCollapseExample{{ payment.id }}">
                                <b><i class="fa fa-list-alt"></i> View Amount Distribution Details</b>
                            </a>

                            <div class="collapse" :id="'multiCollapseExample' + payment.id">
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0 no-margin">
                                        <thead>
                                            <tr>
                                                <th>Account No.</th>
                                                <th>Fees Type</th>
                                                <th>Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(distribution, key) in payment.payment_details.split(';')"
                                                :key="key">
                                                <td>
                                                    {{ printDistributionData(distribution, 'ac_no') }}
                                                </td>
                                                <td>
                                                    {{ printDistributionData(distribution, 'fees') }}
                                                </td>
                                                <td>
                                                    {{ printDistributionData(distribution, 'amount') }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="float-left">
                        <a :href="payment.voucher_url" target="_blank" class="btn btn-info btn-sm">
                            Download voucher
                        </a>
                    </div>

                    <div class="float-right">
                        <a v-if="payment.counter_payment_cancel_url != ''" :href="payment.counter_payment_cancel_url"
                            class="btn btn-danger btn-sm">
                            Cancel payment request
                        </a>
                        <a v-if="payment.counter_payment_confirm_url" :href="payment.counter_payment_confirm_url"
                            class="btn btn-primary btn-sm">
                            Confirm payment request
                        </a>
                    </div>

                    <div class="clearfix"></div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
export default {
    props: {
        encoded_process_type_id: '',
        encoded_ref_id: ''
    },
    data() {
        return {
            app_status: '',
            payment_info: [],
            payment_distribution_types: []

        }
    },
    created() {
        this.getPaymentInfo();
    },
    methods: {
        getPaymentInfo() {
            var app = this;
            axios.get('/spg/vue/payment-view/' + app.encoded_process_type_id + '/' + app.encoded_ref_id).then(response => {
                app.app_status = response.data.app_status;
                app.payment_info = response.data.payment_info;
                app.payment_distribution_types = response.data.payment_distribution_types;
            }).catch(function (error) {
                // handle error
                console.log(error);
            })
                .then(function () {
                    // always executed

                });
        },
        printDistributionData(string, param) {
            const splittedString = string.split(',').map(item => item.trim());

            if (param == 'ac_no') {
                return splittedString[0] !== undefined ? splittedString[0] : 'N/A';
            } else if (param == 'fees') {

                if (splittedString[1] !== undefined) {
                    if (this.payment_distribution_types[splittedString[1]] !== undefined) {
                        return this.payment_distribution_types[splittedString[1]];
                    } else {
                        return 'N/A';
                    }
                }

            } else if (param == 'amount') {
                return splittedString[2] !== undefined ? splittedString[2] : 'N/A';
            } else {
                return 'N/A';
            }
        }
    }
}
</script>

<style>

</style>