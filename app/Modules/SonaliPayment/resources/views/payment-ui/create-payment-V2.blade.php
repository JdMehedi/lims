@if (!in_array($app_status_id, [-1, 5, 0]))
    {!! Form::open(['url' => url('spg/payment/store'), 'method' => 'post', 'class' => 'form-horizontal', 'id' => 'payment_form', 'enctype' => 'multipart/form-data', 'files' => 'true']) !!}
    <input type="hidden" name="encoded_process_type_id" value="{{ $encoded_process_type_id }}"/>
    <input type="hidden" name="encoded_app_id" value="{{ $encoded_app_id }}"/>
    <input type="hidden" name="encoded_payment_step_id" value="{{ $encoded_payment_step_id }}"/>
    <input type="hidden" name="annual_fee_current_year" value="{{isset($payment_info->annualFeeCurrentYear) && $payment_info->annualFeeCurrentYear ? $payment_info->annualFeeCurrentYear : 0}}"/>
    <input type="hidden" name="license_or_annual_fee" value="{{isset($payment_info->LicenseOrAnnualFee) && $payment_info->LicenseOrAnnualFee ? $payment_info->LicenseOrAnnualFee : 0}}"/>
    <input type="hidden" name="license_with_one_year_annual_fee" value="{{isset($payment_info->withOneYearAnnualFee) && $payment_info->withOneYearAnnualFee ? $payment_info->withOneYearAnnualFee : 0}}"/>
    <input type="hidden" name="annual_fee_year_counting" value="{{isset($payment_info->annualFeeYearCounting) && $payment_info->annualFeeYearCounting ? $payment_info->annualFeeYearCounting : 0}}"/>
@endif
<div class="card card-magenta border border-magenta">
    <div class="card-header">
        @if(!empty($payment_name))
            {{$payment_name}}
        @else
            @if(!empty($payment_info->paymentName))
                {{$payment_info->paymentName}}
            @else
                Payment
            @endif
        @endif
{{--        @if($payment_step_id === 1)--}}
{{--            Application Submission Payment--}}
{{--        @elseif($payment_step_id === 2)--}}
{{--            Application License Payment--}}
{{--        @elseif(!in_array($payment_step_id, [1, 2]))--}}
{{--            @if(isset($payment_info->paymentName) && $payment_info->paymentName != '')--}}
{{--                {{$payment_info->paymentName}}--}}
{{--            @else--}}
{{--                Annual Payment--}}
{{--            @endif--}}
{{--        @endif--}}
    </div>
    <div class="card-body">
        @if(in_array($process_type_id, [1, 2]) && in_array($app_status_id,[60]))
            <div class="row form-group">
            <div class="col-md-6">
                <div class="row">
                    <label class="col-md-5 required-star">Select Annual Fee/ BG</label>
                    <div class="col-md-7" style="display: grid; grid-template-columns: auto auto;" id="paymentType">
                        <div>
                            {{ Form::radio('annual_or_bg', 'annual_fee', 'checked', ['class'=>'form-check-input', 'style'=>'margin-left: 0px;', 'id' => 'annual_fee', 'onclick' => "bgOff('annual_fees', 'bg_fees', 'require_check')"]) }}
                            {{ Form::label('annual_fee', 'Annual Fee', ['class' => 'form-check-label','style'=>'margin-left: 20px;', 'onclick' => "bgOff('annual_fees', 'bg_fees', 'require_check')"]) }}
                        </div>
                        <div>
                            {{ Form::radio('annual_or_bg', 'bg_fee', '', ['class'=>'form-check-input', 'style'=>'', 'id' => 'bg_fee', 'onclick' => "bgOn('annual_fees', 'bg_fees', 'require_check')"]) }}
                            {{ Form::label('bg_fee', 'BG', ['class' => 'form-check-label','style'=>'', 'onclick' => "bgOn('annual_fees', 'bg_fees', 'require_check')"])}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6"></div>
        </div>
        @endif
        @if($app_status_id != 64)
            <div class="annual_fees">
                <div class="row form-group">
                    <div class="col-md-6">
                        <div class="row">
                            <label class="col-md-5 required-star">Payment Type</label>
                            <div class="col-md-7" style="display: grid; grid-template-columns: auto auto;" id="paymentType">
                                <div>
                                    {{ Form::radio('payment_type', 'online_payment', '', ['class'=>'form-check-input', 'style'=>'margin-left: 0px;', 'id' => 'online_payment', 'onclick' => "payOrderOff('pay_order_fields', 'online_payment_fields', 'require_check')"]) }}
                                    {{ Form::label('online_payment', 'Online Payment', ['class' => 'form-check-label','style'=>'margin-left: 20px;', 'onclick' => "payOrderOff('pay_order_fields', 'online_payment_fields', 'require_check')"]) }}
                                </div>
                                <div>
                                    {{ Form::radio('payment_type', 'pay_order', 'checked', ['class'=>'form-check-input', 'style'=>'', 'id' => 'pay_order_payment', 'onclick' => "payOrderOn('pay_order_fields', 'online_payment_fields', 'require_check')"]) }}
                                    {{ Form::label('pay_order_payment', 'Pay Order', ['class' => 'form-check-label','style'=>'', 'onclick' => "payOrderOn('pay_order_fields', 'online_payment_fields', 'require_check')"])}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6"></div>
                </div>
                <div class="online_payment_fields">
                    <div class="row form-group">
                        <div class="col-md-6">
                            <div class="row">
                                {!! Form::label('contact_name', 'Contact name', ['class' => 'col-md-5 text-left required-star']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('contact_name', $contact_name, ['class' => 'form-control input-md required', 'id'=> 'contact_name', 'readonly']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                {!! Form::label('contact_email', 'Contact email', ['class' => 'col-md-5 text-left required-star']) !!}
                                <div class="col-md-7">
                                    {!! Form::email('contact_email', $contact_email, ['class' => 'form-control input-md required email', 'id'=> 'contact_email', 'readonly']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-6">
                            <div class="row">
                                {!! Form::label('contact_no', 'Contact phone', ['class' => 'col-md-5 text-left required-star']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('contact_no', $contact_phone, ['class' => 'form-control input-md required phone_or_mobile', 'id'=>'contact_no', 'readonly']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="row">
                                {!! Form::label('contact_address', 'Contact address', ['class' => 'col-md-5 text-left required-star']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('contact_address', $contact_address, ['class' => 'form-control input-md required', 'id'=>'contact_address', 'readonly']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-6">
                            <div class="row">
                                {!! Form::label('pay_amount', 'Pay amount', ['class' => 'col-md-5 text-left']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('pay_amount', $pay_amount, ['class' => 'form-control input-md','id'=>'pay_amount', 'readonly']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                {!! Form::label('vat_on_pay_amount', 'VAT on pay amount', ['class' => 'col-md-5 text-left']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('vat_on_pay_amount', $vat_on_pay_amount, ['class' => 'form-control input-md','id'=>'vat_on_pay_amount', 'readonly']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        @if(isset($payment_info->withOneYearAnnualFee) && $payment_info->withOneYearAnnualFee == 1)
                            <div class="col-md-6">
                                <div class="row">
                                    {!! Form::label('first_annual_fee', '1st Year Annual Fee', ['class' => 'col-md-5 text-left']) !!}
                                    <div class="col-md-7">
                                        {!! Form::text('first_annual_fee', $annual_fee, ['class' => 'form-control input-md','id'=>'first_annual_fee', 'readonly']) !!}
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if(!in_array($payment_step_id, [1]))
                            <div class="col-md-6">
                                <div class="row">
                                    {!! Form::label('online_delay_fee', 'Delay Fee', ['class' => 'col-md-5 text-left']) !!}
                                    <div class="col-md-7">
                                        {!! Form::text('online_delay_fee', $delay_fee, ['class' => 'form-control input-md','id'=>'online_delay_fee', 'readonly']) !!}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="row form-group">
                        <div class="col-md-6">
                            <div class="online_payment_fields">
                                <div class="row">
                                    {!! Form::label('total_amount', 'Total Amount', ['class' => 'col-md-5 text-left']) !!}
                                    <div class="col-md-7">
                                        {!! Form::text('total_amount', $total_amount, ['class' => 'form-control input-md', 'readonly']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
               
                </div>
                
                <div class="col-md-12" style="display: block;margin-bottom:15px;color:red" id="checked_payorder">
                বিটিআরসি’র অর্থ, হিসাব ও রাজস্ব বিভাগে পে-অর্ডারের মূলকপি জমা প্রদান করতঃ এর প্রমাণক (Received Copy) সংযুক্ত করতে হবে
                </div>

                {{--pay order field--}}
                @if(in_array($app_status_id, [46, -1]) || (isset($current_pay_info->is_pay_order_verified) && $current_pay_info->is_pay_order_verified === 0))
                    <div class="pay_order_fields">
                        <div id="pay_order">
                            <div class="row form-group">
                                <div class="col-md-6">
                                    <div class="row">
                                        {!! Form::label('pay_order_contact_name', 'Contact name', ['class' => 'col-md-5 text-left required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('contact_name', "$current_pay_info->contact_name", ['class' => 'form-control input-md require_check required', 'id'=>'pay_order_contact_name','readonly']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        {!! Form::label('pay_order_contact_email', 'Contact email', ['class' => 'col-md-5 text-left required-star']) !!}
                                        <div class="col-md-7" id="pay_order_contact_email_preview">
                                            {!! Form::email('contact_email', "$current_pay_info->contact_email", ['class' => 'form-control input-md require_check required email', 'id'=>'pay_order_contact_email','readonly']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-6">
                                    <div class="row">
                                        {!! Form::label('pay_order_contact_no', 'Contact phone', ['class' => 'col-md-5 text-left required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('contact_no', "$current_pay_info->contact_no", ['class' => 'form-control input-md require_check required phone_or_mobile', 'id'=>'pay_order_contact_no', 'readonly']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        {!! Form::label('pay_order_contact_address', 'Contact address', ['class' => 'col-md-5 text-left required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('contact_address', "$current_pay_info->address", ['class' => 'form-control input-md require_check required', 'id'=>'pay_order_contact_address','readonly']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-6">
                                    <div class="row">
                                        {!! Form::label('pay_order_pay_amount', 'Pay amount', ['class' => 'col-md-5 text-left required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::number('pay_amount', $current_pay_info->pay_amount - $annual_fee, ['class' => 'form-control input-md require_check required', 'id'=>'pay_order_pay_amount', 'readonly']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        {!! Form::label('pay_order_vat_on_pay_amount', 'VAT on pay amount', ['class' => 'col-md-5 text-left required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::number('vat_on_pay_amount', "$current_pay_info->vat_on_pay_amount", ['class' => 'form-control input-md require_check required', 'id'=>'pay_order_vat_on_pay_amount', 'readonly']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row form-group">
                                @if(isset($payment_info->withOneYearAnnualFee) && $payment_info->withOneYearAnnualFee == 1)
                                    <div class="col-md-6">
                                        <div class="row">
                                            {!! Form::label('pay_order_annual_fee', '1st Year Annual Fee', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('pay_order_annual_fee', $annual_fee, ['class' => 'form-control input-md require_check required', 'id'=>'pay_order_annual_fee', 'readonly']) !!}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if(!in_array($payment_step_id, [1]))
                                    <div class="col-md-6">
                                        <div class="row">
                                            {!! Form::label('pay_order_delay_fee', 'Delay Fee', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('pay_order_delay_fee', $delay_fee, ['class' => 'form-control input-md require_check required', 'id'=>'pay_order_delay_fee', 'readonly']) !!}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="row form-group">
                                <div class="col-md-6">
                                    <div class="pay_order_fields">
                                        <div class="row">
                                            {!! Form::label('total_amount', 'Total Amount', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                @if(in_array($app_status_id, [46, -1]) || (isset($current_pay_info->is_pay_order_verified) && $current_pay_info->is_pay_order_verified === 0))
                                                    {!! Form::number('total_amount', "$current_pay_info->total_amount", ['class' => 'form-control input-md require_check required', 'readonly']) !!}
                                                @else
                                                    {!! Form::number('total_amount', $total_amount, ['class' => 'form-control input-md require_check required', 'readonly']) !!}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="pay_order_fields">
                        <div id="pay_order">
                            <div class="row form-group">
                                <div class="col-md-6">
                                    <div class="row">
                                        {!! Form::label('pay_order_contact_name', 'Contact name', ['class' => 'col-md-5 text-left required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('contact_name', $contact_name, ['class' => 'form-control input-md require_check required', 'id'=>'pay_order_contact_name', 'readonly']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        {!! Form::label('pay_order_contact_email', 'Contact email', ['class' => 'col-md-5 text-left required-star']) !!}
                                        <div class="col-md-7" id="pay_order_contact_email_preview">
                                            {!! Form::email('contact_email', $contact_email, ['class' => 'form-control input-md require_check required email', 'id'=>'pay_order_contact_email', 'readonly']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-6">
                                    <div class="row">
                                        {!! Form::label('pay_order_contact_no', 'Contact phone', ['class' => 'col-md-5 text-left required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('contact_no', $contact_phone, ['class' => 'form-control input-md require_check required phone_or_mobile', 'id'=>'pay_order_contact_no', 'readonly']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        {!! Form::label('pay_order_contact_address', 'Contact address', ['class' => 'col-md-5 text-left required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('contact_address', $contact_address, ['class' => 'form-control input-md require_check required', 'id'=>'pay_order_contact_address', 'readonly']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-6">
                                    <div class="row">
                                        {!! Form::label('pay_order_pay_amount', 'Pay amount', ['class' => 'col-md-5 text-left required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::number('pay_amount', $pay_amount, ['class' => 'form-control input-md require_check required', 'id'=>'pay_order_pay_amount', 'readonly']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        {!! Form::label('pay_order_vat_on_pay_amount', 'VAT on pay amount', ['class' => 'col-md-5 text-left required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::number('vat_on_pay_amount', $vat_on_pay_amount, ['class' => 'form-control input-md require_check required', 'id'=>'pay_order_vat_on_pay_amount', 'readonly']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row form-group">
                                @if(isset($payment_info->withOneYearAnnualFee) && $payment_info->withOneYearAnnualFee == 1)
                                    <div class="col-md-6">
                                        <div class="row">
                                            {!! Form::label('pay_order_annual_fee', '1st Year Annual Fee', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('pay_order_annual_fee', $annual_fee, ['class' => 'form-control input-md require_check required', 'id'=>'pay_order_annual_fee', 'readonly']) !!}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if(!in_array($payment_step_id, [1]))
                                    <div class="col-md-6">
                                        <div class="row">
                                            {!! Form::label('pay_order_delay_fee', 'Delay Fee', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('pay_order_delay_fee', $delay_fee, ['class' => 'form-control input-md require_check required', 'id'=>'pay_order_delay_fee', 'readonly']) !!}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="row form-group">
                                <div class="col-md-6">
                                    <div class="pay_order_fields">
                                        <div class="row">
                                            {!! Form::label('total_amount', 'Total Amount', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                @if(in_array($app_status_id, [46, -1]) || (isset($current_pay_info->is_pay_order_verified) && $current_pay_info->is_pay_order_verified === 0))
                                                    {!! Form::number('total_amount', "$current_pay_info->total_amount", ['class' => 'form-control input-md require_check required', 'readonly']) !!}
                                                @else
                                                    {!! Form::number('total_amount', $total_amount, ['class' => 'form-control input-md require_check required', 'readonly']) !!}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Pay Order Information --}}
                <div id="pay_order_information" class="pay_order_fields">
                    @if(empty($multiple_pay_order))
                        <div class="card card-magenta border border-magenta single_pay_order" id="single_pay_order_0">
                            <div class="card-header">
                                Pay Order Information
                                <span style="float: right; cursor: pointer;" class="addPayOrderRow"
                                      onclick="addPayOrderRow('','')">
                    <i style="font-size: 20px;" class="fa fa-plus-square" aria-hidden="true"></i>

            </span>
                            </div>
                            <div class="card-body">
                                <div class="row form-group">
                                    <div class="col-md-6 pay_order_fields">
                                        <div class="row">
                                            {!! Form::label('pay_order_copy', 'Pay Order Copy', ['class' => 'col-md-5 text-left required-star']) !!}
                                            @if(in_array($app_status_id, [46, -1]) || (isset($current_pay_info->is_pay_order_verified) && $current_pay_info->is_pay_order_verified === 0))
                                                <div class="col-md-7">
                                                    <div class="row">
                                                        <div class="col-md-7" id="pay_order_copy_preview">
                                                            <input type="file" style="border: none;" class="form-control input-md"
                                                                   name="pay_order_copy[0]" id="pay_order_copy"
                                                                   onchange="createObjUrl(event, 'pay_order_copy_url_base64', true)"/>
                                                            <input type="hidden" id="pay_order_copy_url_base64"/>
                                                        </div>
                                                        <div class="col-md-5">
                                                            @if(isset($current_pay_info->pay_order_copy))
                                                                <a id="pay_order_exist_link" target="_blank"
                                                                   href="{{url($current_pay_info->pay_order_copy)}}">Pay Order
                                                                    Copy</a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="col-md-7" id="pay_order_copy_preview">
                                                    <input type="file" style="border: none;"
                                                           class="form-control input-md require_check"
                                                           name="pay_order_copy[0]" id="pay_order_copy"
                                                           onchange="createObjUrl(event, 'pay_order_copy_url_base64', true)"/>
                                                    <input type="hidden" id="pay_order_copy_url_base64"/>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="row">
                                            {!! Form::label('pay_order_number', 'Pay Order Number', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('pay_order_number[0]', '', ['class' => 'form-control input-md require_check', 'id' => 'pay_order_number']) !!}
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                @if(in_array($app_status_id, [46, -1]) || (isset($current_pay_info->is_pay_order_verified) && $current_pay_info->is_pay_order_verified === 0))
                                    <div class="pay_order_fields">
                                        <div class="row form-group">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    {!! Form::label('pay_order_number', 'Pay Order Number', ['class' => 'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::number('pay_order_number[0]', "$current_pay_info->pay_order_number", ['class' => 'form-control input-md require_check', 'id' => 'pay_order_number']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    {!! Form::label('pay_order_date', 'Pay Order Date', ['class' => 'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7" id="pay_order_date_preview">
                                                        @if(isset($current_pay_info->pay_order_formated_date))
                                                            <div class="input-group date datetimepicker_pay_order"
                                                                 id="datetimepicker_pay_order" data-target-input="nearest">
                                                                {!! Form::text('pay_order_date[0]',!empty($current_pay_info->pay_order_formated_date) ? \App\Libraries\CommonFunction::changeDateFormat($current_pay_info->pay_order_formated_date) : '', ['class' => 'form-control input-md require_check','data-target'=>'#datetimepicker_pay_order',
                                                                         'data-toggle'=>'datetimepicker', 'id' => 'pay_order_date', 'placeholder' => 'Enter pay order date']) !!}
                                                                <div class="input-group-append"
                                                                     data-target="#datetimepicker_pay_order"
                                                                     data-toggle="datetimepicker">
                                                                    <div class="input-group-text"><i
                                                                            class="fa fa-calendar"></i></div>
                                                                </div>
                                                            </div>

                                                        @else
                                                            <div class="input-group date datetimepicker_pay_order"
                                                                 id="datetimepicker_pay_order" data-target-input="nearest">
                                                                {!! Form::text('pay_order_date[0]', '', ['class' => 'form-control input-md require_check','data-target'=>'#datetimepicker_pay_order',
                                                                     'data-toggle'=>'datetimepicker', 'id' => 'pay_order_date', 'placeholder' => 'Enter pay order date ss']) !!}
                                                                <div class="input-group-append"
                                                                     data-target="#datetimepicker_pay_order"
                                                                     data-toggle="datetimepicker">
                                                                    <div class="input-group-text"><i
                                                                            class="fa fa-calendar"></i></div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row form-group">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    {!! Form::label('bank_name', 'Bank Name', ['class' => 'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('bank_name[0]', [0=>'Select Bank Name'] + $bank_list, "$current_pay_info->bank_id", ['class' => 'form-control input-md require_check','id' => 'bank_name', 'onchange' => "getBranchByBankId('bank_name', this.value, 'branch_name')"]) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    {!! Form::label('branch_name', 'Branch Name', ['class' => 'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('branch_name[0]', [], '', ['class' => "branchId_$current_pay_info->branch_id form-control input-md require_check", 'id' => 'branch_name']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="pay_order_fields">
                                        <div class="row form-group">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    {!! Form::label('pay_order_date', 'Pay Order Date', ['class' => 'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7" id="pay_order_date_preview">
                                                        <div class="input-group date datetimepicker_pay_order"
                                                             id="datetimepicker_pay_order" data-target-input="nearest">
                                                            {!! Form::text('pay_order_date[0]', '', ['class' => 'form-control input-md require_check','data-target'=>'#datetimepicker_pay_order',
                                                                 'data-toggle'=>'datetimepicker', 'id' => 'pay_order_date', 'placeholder' => 'Enter pay order date']) !!}
                                                            <div class="input-group-append"
                                                                 data-target="#datetimepicker_pay_order"
                                                                 data-toggle="datetimepicker">
                                                                <div class="input-group-text"><i
                                                                        class="fa fa-calendar"></i></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    {!! Form::label('bank_name', 'Bank Name', ['class' => 'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('bank_name[0]', [0=>'Select Bank Name'] + $bank_list, '', ['class' => 'form-control input-md require_check','id' => 'bank_name', 'onchange' => "getBranchByBankId('bank_name', this.value, 'branch_name')"]) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row form-group">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    {!! Form::label('branch_name', 'Branch Name', ['class' => 'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('branch_name[0]', [], '', ['class' => 'form-control input-md require_check', 'id' => 'branch_name']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        @foreach($multiple_pay_order as $index => $item)
                            <div class="card card-magenta border border-magenta single_pay_order" id="single_pay_order_{{$loop->index}}">
                                <div class="card-header">
                                    Pay Order Information
                                    @if($loop->index == 0)
                                        <span style="float: right; cursor: pointer;" class="addPayOrderRow"
                                              onclick="addPayOrderRow('')">
                        <i style="font-size: 20px;" class="fa fa-plus-square" aria-hidden="true"></i>
                    </span>
                                    @else
                                        <span class="btn btn-danger cross-button" style="float: right; cursor: pointer;"  onclick="removePayOrder({{$loop->index}})" >
                            <i style="font-size: 16px;" class="fa fa-times" aria-hidden="true"></i>
                        </span>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <div class="row form-group">
                                        <div class="col-md-6 pay_order_fields">
                                            <div class="row">
                                                {!! Form::label('pay_order_copy', 'Pay Order Copy', ['class' => 'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    <div class="row">
                                                        <div class="col-md-7" id="pay_order_copy_preview">
                                                            <input type="file" style="border: none;" class="form-control input-md"
                                                                   name="pay_order_copy[{{$index}}]" id="pay_order_copy"
                                                                   onchange="createObjUrl(event, 'pay_order_copy_url_base64', true)"/>
                                                            <input type="hidden" id="pay_order_copy_url_base64"/>
                                                            <input type="hidden" name="pay_order_exists[{{$index}}]" value="{{$item->pay_order_copy}}">
                                                        </div>
                                                        <div class="col-md-5">
                                                            @if(isset($item->pay_order_copy))
                                                                <a id="pay_order_exist_link" target="_blank"
                                                                   href="{{url($item->pay_order_copy)}}">Pay Order
                                                                    Copy</a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="row">
                                                {!! Form::label('pay_order_number', 'Pay Order Number', ['class' => 'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::number("pay_order_number[$index]", $item->pay_order_num, ['class' => 'form-control input-md require_check', 'id' => 'pay_order_number']) !!}
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="pay_order_fields">
                                        <div class="row form-group">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    {!! Form::label('pay_order_date', 'Pay Order Date', ['class' => 'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7" id="pay_order_date_preview">
                                                        @if(isset($item->pay_order_date))
                                                            <div class="input-group date datetimepicker_pay_order"
                                                                 id="datetimepicker_pay_order" data-target-input="nearest">
                                                                {!! Form::text("pay_order_date[$index]",!empty($item->pay_order_date) ? \App\Libraries\CommonFunction::changeDateFormat($item->pay_order_date) : '', ['class' => 'form-control input-md require_check','data-target'=>'#datetimepicker_pay_order',
                                                                         'data-toggle'=>'datetimepicker', 'id' => 'pay_order_date', 'placeholder' => 'Enter pay order date']) !!}
                                                                <div class="input-group-append"
                                                                     data-target="#datetimepicker_pay_order"
                                                                     data-toggle="datetimepicker">
                                                                    <div class="input-group-text"><i
                                                                            class="fa fa-calendar"></i></div>
                                                                </div>
                                                            </div>
                                                        @endif


                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    {!! Form::label('bank_name', 'Bank Name', ['class' => 'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select("bank_name[$index]", [0=>'Select Bank Name'] + $bank_list, "$item->bank_id", ['class' => 'form-control input-md require_check','id' => "bank_name_$index", 'onchange' => 'getBranchByBankId("bank_name_'.$index.'", this.value, "branch_name_'.$index.'")']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row form-group">

                                            <div class="col-md-6">
                                                <div class="row">
                                                    {!! Form::label('branch_name', 'Branch Name', ['class' => 'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select("branch_name[$index]", [], '', ['class' => "branchId_$item->branch_id form-control input-md require_check", 'id' => "branch_name_$index"]) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                @if(in_array($process_type_id,['21','22','50','51']))
                    {{-- Pay Order Information --}}
                    @if($app_status_id > 1)
                        {{-- Bank guarentee fields --}}
                        @if(in_array($app_status_id, [46, -1]) || (isset($current_pay_info->is_pay_order_verified) && $current_pay_info->is_pay_order_verified === 0))
                            <div class="card card-magenta border border-magenta online_payment_fields">
                                <div class="card-header">
                                    Bank Guarantee
                                </div>
                                <div class="card-body">
                                    <div id="bank_guarantee">
                                        <div class="row form-group">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    {!! Form::label('bg_bank_name', 'Bank Name', ['class' => 'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('bg_bank_name', [0=>'Select Bank Name'] + $bank_list, "$current_pay_info->bg_bank_id", ['class' => 'form-control input-md require_check','id' => 'bg_bank_name', 'onchange' => "getBranchByBankId('bg_bank_name', this.value, 'bg_branch_name')"]) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    {!! Form::label('bg_branch_name', 'Branch Name', ['class' => 'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('bg_branch_name', [], '', ['class' => "bgBranchId_$current_pay_info->bg_branch_id form-control input-md require_check", 'id' => 'bg_branch_name']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    {!! Form::label('bg_expire_date', 'Expire Date', ['class' => 'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7" id="bg_expire_date_preview">
                                                        <div class="input-group date datetimepicker_expire_pay_order_date"
                                                             id="datetimepicker_expire_pay_order_date" data-target-input="nearest">
                                                            @if(isset($current_pay_info->bg_expire_formated_date))
                                                                {!! Form::text('bg_expire_date', !empty($current_pay_info->bg_expire_formated_date) ? \App\Libraries\CommonFunction::changeDateFormat($current_pay_info->bg_expire_formated_date) : '', ['class' => 'form-control input-md require_check','data-target'=>'#datetimepicker_expire_pay_order_date',
                                                                     'data-toggle'=>'datetimepicker', 'id' => 'bg_expire_date']) !!}
                                                                {{--                                                {!! Form::date('bg_expire_date', "$current_pay_info->bg_expire_formated_date", ['class' => 'form-control input-md require_check', 'id' => 'bg_expire_date']) !!}--}}
                                                            @else
                                                                {!! Form::text('bg_expire_date', "", ['class' => 'form-control input-md require_check','data-target'=>'#datetimepicker_expire_pay_order_date',
                                                                     'data-toggle'=>'datetimepicker', 'id' => 'bg_expire_date']) !!}
                                                            @endif
                                                            <div class="input-group-append"
                                                                 data-target="#datetimepicker_expire_pay_order_date"
                                                                 data-toggle="datetimepicker">
                                                                <div class="input-group-text"><i
                                                                        class="fa fa-calendar"></i></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6" id="pay_copy">
                                                <div class="row">
                                                    {!! Form::label('bg_copy', 'Bank Guarantee Copy', ['class' => 'col-md-5 text-left required-star']) !!}
                                                    @if(in_array($app_status_id, [46, -1]) || (isset($current_pay_info->is_pay_order_verified) && $current_pay_info->is_pay_order_verified === 0))
                                                        <div class="col-md-7">
                                                            <div class="row">
                                                                <div class="col-md-7" id="bg_copy_url_base64_preview">
                                                                    <input type="file" style="border: none;"
                                                                           class="form-control input-md" name="bg_copy" id="bg_copy"
                                                                           onchange="createObjUrl(event, 'bg_copy_url_base64', true)"/>
                                                                    <input type="hidden" id="bg_copy_url_base64"/>
                                                                </div>
                                                                <div class="col-md-5">
                                                                    @if(isset($current_pay_info->bg_copy))
                                                                        <a id="bank_guarantee_exist_link" target="_blank"
                                                                           href="{{url($current_pay_info->bg_copy)}}">Bank Guarantee</a>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="col-md-7" id="bg_copy_url_base64_preview">
                                                            <input type="file" style="border: none;"
                                                                   class="form-control input-md require_check" name="bg_copy"
                                                                   id="bg_copy" onchange="createObjUrl(event, 'bg_copy_url_base64', true)"/>
                                                            <input type="hidden" id="bg_copy_url_base64"/>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card card-magenta border border-magenta pay_order_fields">
                                <div class="card-header">
                                    Bank Guarantee
                                </div>
                                <div class="card-body">
                                    <div id="bank_guarantee">
                                        <div class="row form-group">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    {!! Form::label('bg_bank_name', 'Bank Name', ['class' => 'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('bg_bank_name', [0=>'Select Bank Name'] + $bank_list, "$current_pay_info->bg_bank_id", ['class' => 'form-control input-md require_check','id' => 'bg_bank_name_1', 'onchange' => "getBranchByBankId('bg_bank_name_1', this.value, 'bg_branch_name_1')"]) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    {!! Form::label('bg_branch_name', 'Branch Name', ['class' => 'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('bg_branch_name', [], '', ['class' => "bgBranchId_$current_pay_info->bg_branch_id form-control input-md require_check", 'id' => 'bg_branch_name_1']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    {!! Form::label('bg_expire_date', 'Expire Date', ['class' => 'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7" id="bg_expire_date_preview">
                                                        <div class="input-group date datetimepicker_expire_pay_order_date"
                                                             id="datetimepicker_shortfall" data-target-input="nearest">
                                                            @if(isset($current_pay_info->bg_expire_date))
                                                                {!! Form::text('bg_expire_date', !empty($current_pay_info->bg_expire_date) ? \App\Libraries\CommonFunction::changeDateFormat($current_pay_info->bg_expire_date) : '', ['class' => 'form-control input-md require_check','data-target'=>'#datetimepicker_expire_pay_order_date',
                                                                     'data-toggle'=>'datetimepicker', 'id' => 'bg_expire_date']) !!}
                                                                {{--                                                {!! Form::date('bg_expire_date', "$current_pay_info->bg_expire_formated_date", ['class' => 'form-control input-md require_check', 'id' => 'bg_expire_date']) !!}--}}
                                                            @else
                                                                {!! Form::text('bg_expire_date', "", ['class' => 'form-control input-md require_check','data-target'=>'#datetimepicker_expire_pay_order_date',
                                                                     'data-toggle'=>'datetimepicker', 'id' => 'bg_expire_date']) !!}
                                                            @endif
                                                            <div class="input-group-append"
                                                                 data-target="#datetimepicker_shortfall"
                                                                 data-toggle="datetimepicker">
                                                                <div class="input-group-text"><i
                                                                        class="fa fa-calendar"></i></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6" id="pay_copy">
                                                <div class="row">
                                                    {!! Form::label('bg_copy', 'Bank Guarantee Copy', ['class' => 'col-md-5 text-left required-star']) !!}
                                                    @if(in_array($app_status_id, [46, -1]) || (isset($current_pay_info->is_pay_order_verified) && $current_pay_info->is_pay_order_verified === 0))
                                                        <div class="col-md-7">
                                                            <div class="row">
                                                                <div class="col-md-7" id="bg_copy_url_base64_preview">
                                                                    <input type="file" style="border: none;"
                                                                           class="form-control input-md" name="bg_copy" id="bg_copy"
                                                                           onchange="createObjUrl(event, 'bg_copy_url_base64', true)"/>
                                                                    <input type="hidden" id="bg_copy_url_base64"/>
                                                                </div>
                                                                <div class="col-md-5">
                                                                    @if(isset($current_pay_info->bg_copy))
                                                                        <a id="bank_guarantee_exist_link" target="_blank"
                                                                           href="{{url($current_pay_info->bg_copy)}}">Bank Guarantee</a>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="col-md-7" id="bg_copy_url_base64_preview">
                                                            <input type="file" style="border: none;"
                                                                   class="form-control input-md require_check" name="bg_copy"
                                                                   id="bg_copy" onchange="createObjUrl(event, 'bg_copy_url_base64', true)"/>
                                                            <input type="hidden" id="bg_copy_url_base64"/>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @else
                            <div class="card card-magenta border border-magenta online_payment_fields">
                                <div class="card-header">
                                    Bank Guarantee
                                </div>
                                <div class="card-body">
                                    <div id="bank_guarantee">
                                        <div class="row form-group">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    {!! Form::label('bg_bank_name', 'Bank Name', ['class' => 'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('bg_bank_name', [0=>'Select Bank Name'] + $bank_list, '', ['class' => 'form-control input-md require_check','id' => 'bg_bank_name', 'onchange' => "getBranchByBankId('bg_bank_name', this.value, 'bg_branch_name')"]) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    {!! Form::label('bg_branch_name', 'Branch Name', ['class' => 'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('bg_branch_name', [], '', ['class' => 'form-control input-md require_check', 'id' => 'bg_branch_name']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    {!! Form::label('bg_expire_date', 'Expire Date', ['class' => 'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7" id="bg_expire_date_preview">

                                                        <div class="input-group date datetimepicker_expire_pay_order_date"
                                                             id="datetimepicker_expire_online_date" data-target-input="nearest">
                                                            {!! Form::text('bg_expire_date', '', ['class' => 'form-control input-md require_check','data-target'=>'#datetimepicker_expire_pay_order_date',
                                                                 'data-toggle'=>'datetimepicker', 'id' => 'bg_expire_date']) !!}
                                                            <div class="input-group-append"
                                                                 data-target="#datetimepicker_expire_online_date"
                                                                 data-toggle="datetimepicker">
                                                                <div class="input-group-text"><i
                                                                        class="fa fa-calendar"></i></div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6" id="pay_copy">
                                                <div class="row">
                                                    {!! Form::label('bg_copy', 'Bank Guarantee Copy', ['class' => 'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7" id="bg_copy_url_base64_preview">
                                                        <input type="file" style="border: none;"
                                                               class="form-control input-md require_check" name="bg_copy" id="bg_copy"
                                                               onchange="createObjUrl(event, 'bg_copy_url_base64', true)"/>
                                                        <input type="hidden" id="bg_copy_url_base64"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card card-magenta border border-magenta pay_order_fields">
                                <div class="card-header">
                                    Bank Guarantee
                                </div>
                                <div class="card-body">
                                    <div id="bank_guarantee">
                                        <div class="row form-group">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    {!! Form::label('bg_bank_name', 'Bank Name', ['class' => 'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('bg_bank_name', [0=>'Select Bank Name'] + $bank_list, '', ['class' => 'form-control input-md require_check','id' => 'bg_bank_name2', 'onchange' => "getBranchByBankId('bg_bank_name2', this.value, 'bg_branch_name2')"]) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    {!! Form::label('bg_branch_name', 'Branch Name', ['class' => 'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('bg_branch_name', [], '', ['class' => 'form-control input-md require_check', 'id' => 'bg_branch_name2']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    {!! Form::label('bg_expire_date', 'Expire Date', ['class' => 'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7" id="bg_expire_date_preview">

                                                        <div class="input-group date datetimepicker_expire_pay_order_date"
                                                             id="datetimepicker_expire_pay_order_date" data-target-input="nearest">
                                                            {!! Form::text('bg_expire_date', '', ['class' => 'form-control input-md require_check','data-target'=>'#datetimepicker_expire_pay_order_date',
                                                                 'data-toggle'=>'datetimepicker', 'id' => 'bg_expire_date']) !!}
                                                            <div class="input-group-append"
                                                                 data-target="#datetimepicker_expire_pay_order_date"
                                                                 data-toggle="datetimepicker">
                                                                <div class="input-group-text"><i
                                                                        class="fa fa-calendar"></i></div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6" id="pay_copy">
                                                <div class="row">
                                                    {!! Form::label('bg_copy', 'Bank Guarantee Copy', ['class' => 'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7" id="bg_copy_url_base64_preview">
                                                        <input type="file" style="border: none;"
                                                               class="form-control input-md require_check" name="bg_copy" id="bg_copy"
                                                               onchange="createObjUrl(event, 'bg_copy_url_base64', true)"/>
                                                        <input type="hidden" id="bg_copy_url_base64"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                @endif

                <div class="row form-group">
                    {{--            <div class="col-md-6 online_payment_fields">--}}
                    {{--                <div class="row">--}}
                    {{--                    {!! Form::label('total_amount', 'Total Amount', ['class' => 'col-md-5 text-left']) !!}--}}
                    {{--                    <div class="col-md-7">--}}
                    {{--                        {!! Form::number('total_amount', $total_amount, ['class' => 'form-control input-md', 'readonly', 'id'=>'total_amount']) !!}--}}
                    {{--                    </div>--}}
                    {{--                </div>--}}
                    {{--            </div>--}}
                    {{--            <div class="col-md-6 pay_order_fields">--}}
                    {{--                <div class="row">--}}
                    {{--                    {!! Form::label('pay_order_total_amount', 'Total Amount', ['class' => 'col-md-5 text-left required-star']) !!}--}}
                    {{--                    <div class="col-md-7">--}}
                    {{--                        @if(in_array($app_status_id, [46]) || (isset($current_pay_info->is_pay_order_verified) && $current_pay_info->is_pay_order_verified === 0))--}}
                    {{--                            {!! Form::number('total_amount', "$current_pay_info->total_amount", ['class' => 'form-control input-md required', 'id'=>'pay_order_total_amount']) !!}--}}
                    {{--                        @else--}}
                    {{--                            {!! Form::number('total_amount', '', ['class' => 'form-control input-md required', 'id'=>'pay_order_total_amount']) !!}--}}
                    {{--                        @endif--}}
                    {{--                    </div>--}}
                    {{--                </div>--}}
                    {{--            </div>--}}

                    <div class="col-md-6">
                        <div class="row">
                            {!! Form::label('payment_status', 'Payment Status', ['class' => 'col-md-5 text-left']) !!}
                            <div class="col-md-7">
                                <span class="label label-warning">Not Paid</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row online_payment_fields">
                    <div class="col-md-12">
                        <div class="alert alert-warning no-margin mb-0" role="alert">
                            <b>Vat/ Tax</b> and <b>Transaction charge</b> is an approximate amount, those may vary based on the
                            Sonali Bank system and those will be visible here after payment submission.
                        </div>
                    </div>
                </div>
            </div>
        @endif
         @if(in_array($process_type_id, [1, 2]) && in_array($app_status_id, ['64', '60']))
            <div class="bg_fees" style="{{($app_status_id == 64) ? '' : 'display: none;'}}">
                @if(in_array($app_status_id,[64]))
                    <input type="hidden" name="annual_or_bg" value="bg_fee" />
                    <div class="row form-group">
                        <div class="col-md-6">
                            <div class="row">
                                {!! Form::label('bank_guarantee_amount', 'Bank Guarantee Amount:', ['class' => 'col-md-5 text-left required-star']) !!}
                                <div class="col-md-7">
                                    {!! Form::number('bank_guarantee_amount', $bank_guarantee_amount, ['class' => 'form-control input-md require_check required', 'id'=>'bank_guarantee_amount', 'readonly']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card card-magenta border border-magenta pay_order_fields">
                        <div class="card-header">
                            Bank Guarantee
                        </div>
                        <div class="card-body">
                            <div id="bank_guarantee">
                                <div class="row form-group">
                                    <div class="col-md-6">
                                        <div class="row">
                                            {!! Form::label('bg_bank_name', 'Bank Name', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('bg_bank_name', [0=>'Select Bank Name'] + $bank_list, "$current_pay_info->bg_bank_id", ['class' => 'form-control input-md require_check','id' => 'bg_bank_name_1', 'onchange' => "getBranchByBankId('bg_bank_name_1', this.value, 'bg_branch_name_1')"]) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            {!! Form::label('bg_branch_name', 'Branch Name', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('bg_branch_name', [], '', ['class' => "bgBranchId_$current_pay_info->bg_branch_id form-control input-md require_check", 'id' => 'bg_branch_name_1']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-md-6">
                                        <div class="row">
                                            {!! Form::label('bg_expire_date', 'Expire Date', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7" id="bg_expire_date_preview">
                                                <div class="input-group date datetimepicker_expire_pay_order_date"
                                                     id="datetimepicker_shortfall" data-target-input="nearest">
                                                    @if(isset($current_pay_info->bg_expire_date))
                                                        {!! Form::text('bg_expire_date', !empty($current_pay_info->bg_expire_date) ? \App\Libraries\CommonFunction::changeDateFormat($current_pay_info->bg_expire_date) : '', ['class' => 'form-control input-md require_check','data-target'=>'#datetimepicker_expire_pay_order_date',
                                                             'data-toggle'=>'datetimepicker', 'id' => 'bg_expire_date']) !!}
                                                        {{--                                                {!! Form::date('bg_expire_date', "$current_pay_info->bg_expire_formated_date", ['class' => 'form-control input-md require_check', 'id' => 'bg_expire_date']) !!}--}}
                                                    @else
                                                        {!! Form::text('bg_expire_date', "", ['class' => 'form-control input-md require_check','data-target'=>'#datetimepicker_expire_pay_order_date',
                                                             'data-toggle'=>'datetimepicker', 'id' => 'bg_expire_date']) !!}
                                                    @endif
                                                    <div class="input-group-append"
                                                         data-target="#datetimepicker_shortfall"
                                                         data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i
                                                                class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="pay_copy">
                                        <div class="row">
                                            {!! Form::label('bg_copy', 'Bank Guarantee Copy', ['class' => 'col-md-5 text-left required-star']) !!}
                                            @if(in_array($app_status_id, [46, -1]) || (isset($current_pay_info->is_pay_order_verified) && $current_pay_info->is_pay_order_verified === 0))
                                                <div class="col-md-7">
                                                    <div class="row">
                                                        <div class="col-md-7" id="bg_copy_url_base64_preview">
                                                            <input type="file" style="border: none;"
                                                                   class="form-control input-md" name="bg_copy" id="bg_copy"
                                                                   onchange="createObjUrl(event, 'bg_copy_url_base64', true)"/>
                                                            <input type="hidden" id="bg_copy_url_base64"/>
                                                        </div>
                                                        <div class="col-md-5">
                                                            @if(isset($current_pay_info->bg_copy))
                                                                <a id="bank_guarantee_exist_link" target="_blank"
                                                                   href="{{url($current_pay_info->bg_copy)}}">Bank Guarantee</a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="col-md-7" id="bg_copy_url_base64_preview">
                                                    <input type="file" style="border: none;"
                                                           class="form-control input-md require_check" name="bg_copy"
                                                           id="bg_copy" onchange="createObjUrl(event, 'bg_copy_url_base64', true)"/>
                                                    <input type="hidden" id="bg_copy_url_base64"/>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row form-group">
                        <div class="col-md-6">
                            <div class="row">
                                {!! Form::label('bank_guarantee_amount', 'Bank Guarantee Amount:', ['class' => 'col-md-5 text-left required-star']) !!}
                                <div class="col-md-7">
                                    {!! Form::number('bank_guarantee_amount', $bank_guarantee_amount, ['class' => 'form-control input-md require_check required', 'id'=>'bank_guarantee_amount', 'readonly']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card card-magenta border border-magenta bg_payment_fields">
                        <div class="card-header">
                            Bank Guarantee
                        </div>
                        <div class="card-body">
                            <div id="bank_guarantee">
                                <div class="row form-group">
                                    <div class="col-md-6">
                                        <div class="row">
                                            {!! Form::label('bg_bank_name', 'Bank Name', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('bg_bank_name', [0=>'Select Bank Name'] + $bank_list, '', ['class' => 'form-control input-md require_check','id' => 'bg_bank_name', 'onchange' => "getBranchByBankId('bg_bank_name', this.value, 'bg_branch_name')"]) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            {!! Form::label('bg_branch_name', 'Branch Name', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('bg_branch_name', [], '', ['class' => 'form-control input-md require_check', 'id' => 'bg_branch_name']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-md-6">
                                        <div class="row">
                                            {!! Form::label('bg_expire_date', 'Expire Date', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7" id="bg_expire_date_preview">

                                                <div class="input-group date datetimepicker_expire_pay_order_date"
                                                     id="datetimepicker_expire_online_date" data-target-input="nearest">
                                                    {!! Form::text('bg_expire_date', '', ['class' => 'form-control input-md require_check','data-target'=>'#datetimepicker_expire_pay_order_date',
                                                         'data-toggle'=>'datetimepicker', 'id' => 'bg_expire_date']) !!}
                                                    <div class="input-group-append"
                                                         data-target="#datetimepicker_expire_online_date"
                                                         data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i
                                                                class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="pay_copy">
                                        <div class="row">
                                            {!! Form::label('bg_copy', 'Bank Guarantee Copy', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7" id="bg_copy_url_base64_preview">
                                                <input type="file" style="border: none;"
                                                       class="form-control input-md require_check" name="bg_copy" id="bg_copy"
                                                       onchange="createObjUrl(event, 'bg_copy_url_base64', true)"/>
                                                <input type="hidden" id="bg_copy_url_base64"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif

    </div>
    @if (!in_array($app_status_id, [-1, 5, 46, 0, 64]))
        <div class="card-footer text-right">
            <button type="button" class="btn btn-success" onclick="checkPaymentType('payment_form', 'require_check')">
                Submit Payment
            </button>
        </div>
    @elseif(in_array($app_status_id, [46, 64]))
        <div class="card-footer text-right">
            <button type="button" class="btn btn-success" onclick="checkPaymentType('payment_form', 'require_check', '{{$app_status_id}}')">Re
                Submit Payment
            </button>
        </div>
    @endif
</div>

@if (!in_array($app_status_id, [-1, 5]))
    {!! Form::close() !!}
@endif
