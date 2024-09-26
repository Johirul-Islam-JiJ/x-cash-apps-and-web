@extends($activeTemplate . 'layouts.user_master')
@section('content')
@php
$class = '';
if (userGuard()['type'] == 'AGENT' || userGuard()['type'] == 'MERCHANT') {
$class = 'mt-5';
}
@endphp


<div class="col-xl-12">




    <div class="card style--two">
        <div class="card-header d-flex flex-wrap align-items-center justify-content-center">
            <div class="bank-icon  me-2">
                <i class="las la-wallet"></i>
            </div>
            <h4 class="fw-normal">@lang($pageTitle)</h4>
        </div>

        {{-- Ad Money --}}

        <form action="{{ route(strtolower(userGuard()['type']) . '.currency.exchange.store') }}" method="POST" id="form">
            @csrf
            <div class="row justify-content-center gy-4 {{ $class }}">
                <!-- Send From Section -->
                <div class="col-lg-6">
                    <div class="add-money-card">
                        <h4 class="title"><i class="las la-paper-plane"></i> @lang('Send From')</h4>
                        <input type="hidden" name="currency">
                        <input type="hidden" name="currency_id">
                        <div class="form-group">
                            <label>@lang('Select Gateway')</label>
                            <select class="select send-gateway" name="method_code" required>
                                <option value="">@lang('Select Gateway')</option>
                                @foreach ($gatewayCurrency as $data)
                                <option value="{{ $data->method_code }}"
                                    data-gateway='@json($data)'>{{ __($data->name) }}</option>
                                @endforeach
                            </select>
                            <code class="text--danger gateway-msg"></code>
                        </div>

                        <div class="form-group mb-0">
                            <label>@lang('Amount')</label>
                            <div class="input-group">
                                <input class="form--control send-amount" type="number" step="any" name="amount"
                                    disabled placeholder="Enter Amount" required>
                                <span class="input-group-text send-curr_code"></span>
                            </div>
                            <p><code class="text--warning limit send-limit">@lang('limit') : 0.00 <span
                                        class="send-curr_code"></span></code></p>
                            <p>@lang('Processing Fees'): <span class="send-processing-fee">0.00</span> <span
                                    class="send-curr_code"></span></p>
                            <p>@lang('Total to Send'): <span class="send-final-amount">0.00</span> <span
                                    class="send-curr_code"></span></p>
                        </div>
                    </div>
                </div>

                <!-- Received To Section -->
                <div class="col-lg-6">
                    <div class="add-money-card">
                        <h4 class="title"><i class="las la-download"></i> @lang('Received To')</h4>

                        <div class="form-group">
                            <label>@lang('Select Gateway')</label>
                            <select class="select receive-gateway" name="withdraw_method_id" required>
                                <option value="">@lang('Select Gateway')</option>
                                @foreach ($withdrawMethod as $data)
                                <option value="{{ $data->id }}"
                                    data-gateway='@json($data)'>{{ __($data->name) }}</option>
                                @endforeach
                            </select>
                            <code class="text--danger gateway-msg"></code>
                        </div>

                        <div class="form-group mb-0">
                            <label>@lang('Amount')</label>
                            <div class="input-group">
                                <input class="form--control receive-amount" type="number" value="" step="any" name="withdraw_amount"
                                    disabled placeholder="Received Amount" required>
                                <span class="input-group-text receive-curr_code"></span>
                            </div>
                            <p><code class="text--warning limit receive-limit">@lang('limit') : 0.00 <span
                                        class="receive-curr_code"></span></code></p>
                            <p>@lang('Processing Fees'): <span class="receive-processing-fee">0.00</span> <span
                                    class="receive-curr_code"></span></p>
                            <p>@lang('Total to Receive'): <span class="receive-final-amount">0.00</span> <span
                                    class="receive-curr_code"></span></p>
                        </div>
                    </div>
                </div>

                <button class="btn btn--base w-100">@lang('Exchange')</button>
            </div>
        </form>

    </div>
</div>

@endsection

@push('script')
<script>
    "use strict";
    (function($) {
        let sendAmount = 0;
        let sendGateway = null,
            receiveGateway = null;
        let sendMinAmount, sendMaxAmount, receiveMinAmount, receiveMaxAmount;

        $('.send-amount').on('input', function(e) {
            sendAmount = parseFloat($(this).val()) || 0;
            updateSendAmount();
            updateReceiveAmount();
        });

        $('.send-gateway').on('change', function(e) {
            let gatewayElement = $('.send-gateway option:selected');
            sendGateway = gatewayElement.data('gateway');
            sendMinAmount = sendGateway.min_amount;
            sendMaxAmount = sendGateway.max_amount;

            // Update the currency and enable the amount field
            $(".send-curr_code").text(sendGateway.currency);
            $('input[name=currency]').val(sendGateway.currency)
            $(".send-amount").removeAttr('disabled');
            updateSendAmount();
        });

        // Event for handling the selection of the "Receive To" gateway
        $('.receive-gateway').on('change', function(e) {
            let gatewayElement = $('.receive-gateway option:selected');
            receiveGateway = gatewayElement.data('gateway');
            receiveMinAmount = receiveGateway.min_limit;
            receiveMaxAmount = receiveGateway.max_limit;

            // Update the currency and enable the amount field
            $(".receive-curr_code").text(receiveGateway.currency);
           
            updateReceiveAmount();
        });

        // Function to calculate and update the "Send From" total with charges
        function updateSendAmount() {
            if (!sendGateway) return;

            let percentCharge = parseFloat(sendGateway.percent_charge) || 0;
            let fixedCharge = parseFloat(sendGateway.fixed_charge) || 0;
            let totalPercentCharge = (sendAmount / 100) * percentCharge;
            let totalCharge = totalPercentCharge + fixedCharge;
            let totalToSend = sendAmount + totalCharge;

            $(".send-final-amount").text(totalToSend.toFixed(2));
            $(".send-processing-fee").text(totalCharge.toFixed(2));
            $(".send-limit").text(`${sendMinAmount} - ${sendMaxAmount}`);

            if (sendAmount < sendMinAmount || sendAmount > sendMaxAmount) {
                $(".deposit-form button[type=submit]").attr('disabled', true);
            } else {
                $(".deposit-form button[type=submit]").removeAttr('disabled');
            }
        }

        // Function to calculate and update the "Receive To" total after deducting charges
        function updateReceiveAmount() {
            if (!receiveGateway || sendAmount === 0) return;

            let percentCharge = parseFloat(receiveGateway.percent_charge) || 0;
            let fixedCharge = parseFloat(receiveGateway.fixed_charge) || 0;
            let totalPercentCharge = (sendAmount / 100) * percentCharge;
            let totalCharge = totalPercentCharge + fixedCharge;
            let totalReceived = sendAmount - totalCharge;
            let rate = receiveGateway.rate;
            let rcv = totalReceived * rate;
            let chrg = totalCharge * rate;

            
            $(".receive-amount").val(rcv.toFixed(2));

            $(".receive-final-amount").text(rcv.toFixed(2));
            $(".receive-processing-fee").text(chrg.toFixed(2));
            $(".receive-limit").text(`${receiveMinAmount} - ${receiveMaxAmount}`);

            if (sendAmount < receiveMinAmount || sendAmount > receiveMaxAmount) {
                $(".withdraw-form button[type=submit]").attr('disabled', true);
            } else {
                $(".withdraw-form button[type=submit]").removeAttr('disabled');
            }
        }

        // Initialize with selected values (if any)
        $('.send-gateway').change();
        $('.receive-gateway').change();

    })(jQuery);
</script>
@endpush