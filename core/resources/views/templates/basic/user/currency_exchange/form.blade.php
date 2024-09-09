@extends($activeTemplate . 'layouts.user_master')
@section('content')
    @php
        $class = '';
        if (userGuard()['type'] == 'AGENT' || userGuard()['type'] == 'MERCHANT') {
            $class = 'mt-5';
        }
    @endphp


    <div class="col-xl-10">




        <div class="card style--two">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-center">
                <div class="bank-icon  me-2">
                    <i class="las la-wallet"></i>
                </div>
                <h4 class="fw-normal">@lang($pageTitle)</h4>
            </div>

            {{-- Ad Money --}}

            <form action="{{ route(strtolower(userGuard()['type']) . '.deposit.insert') }}" method="POST" id="form">
                @csrf
                <div class="row justify-content-center gy-4 {{ $class }}">
                    <div class="col-lg-6">
                        <div class="add-money-card">
                            <h4 class="title"><i class="las la-plus-circle"></i> @lang('Add Money')</h4>
                            <div class="form-group">
                                <label>@lang('Select Your Wallet')</label>
                                <input type="hidden" name="currency">
                                <input type="hidden" name="currency_id">
                                <select class="select" name="wallet_id" id="wallet" required>
                                    <option>@lang('Select Currency')</option>
                                    @foreach (userGuard()['user']->wallets as $wallet)
                                        <option value="{{ $wallet->id }}"
                                            data-code="{{ $wallet->currency->currency_code }}"
                                            data-sym="{{ $wallet->currency->currency_symbol }}"
                                            data-rate="{{ $wallet->currency->rate }}"
                                            data-currency="{{ $wallet->currency->id }}"
                                            data-type="{{ $wallet->currency->currency_type }}"
                                            data-gateways="{{ $wallet->gateways() }}">
                                            @lang($wallet->currency->currency_code)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>@lang('Select Gateway')</label>
                                <select class="select gateway" name="method_code" disabled required>
                                    <option value="">@lang('Select Gateway')</option>
                                </select>
                                <code class="text--danger gateway-msg"></code>
                            </div>
                            <div class="form-group mb-0">
                                <label>@lang('Amount')</label>
                                <div class="input-group">
                                    <input class="form--control amount" type="number" step="any" name="amount"
                                        disabled placeholder="Enter Amount" required>
                                    <span class="input-group-text curr_code"></span>
                                </div>
                                <p><code class="text--warning limit">@lang('limit') : 0.00 <span
                                            class="curr_code"></span></code>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="add-money-card style--two">
                            <h4 class="title"><i class="lar la-file-alt"></i> @lang('Summery')</h4>
                            <div class="add-moeny-card-middle">
                                <ul class="add-money-details-list">
                                    <li>
                                        <span class="caption">@lang('Amount')</span>
                                        <div class="value">
                                            <span class="sym">{{ $general->cur_sym }}</span><span
                                                class="show-amount">0.00</span>
                                        </div>
                                    </li>
                                    <li>
                                        <span class="caption">@lang('Charge')</span>
                                        <div class="value">
                                            <span class="sym">{{ $general->cur_sym }}</span><span
                                                class="charge">0.00</span>
                                        </div>
                                    </li>
                                </ul>
                                <div class="add-money-details-bottom">
                                    <span class="caption">@lang('Payable')</span>
                                    <div class="value">
                                        <span class="sym">{{ $general->cur_sym }}</span><span
                                            class="payable">0.00</span>
                                    </div>
                                </div>
                            </div>
                            <button type="submit"
                                class="btn btn-md btn--base w-100 mt-3 req_confirm">@lang('Proceed')</button>
                        </div>
                    </div>
                </div>
            </form>


            {{-- Currency Converter --}}
            <div class="card-body p-4">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <form action="" method="POST" id="form">
                            @csrf
                            <div class="d-widget">
                                <div class="d-widget__header">
                                    <h6>@lang('Exchange')</h4>
                                </div>
                                <div class="d-widget__content px-5">
                                    <div class="p-4 border mb-4">
                                        <div class="row">
                                            <div class="col-lg-12 form-group">
                                                <label class="mb-0">@lang('Amount')<span class="text--danger">*</span>
                                                </label>
                                                <input type="number" step="any" class="form--control style--two amount"
                                                    name="amount" placeholder="0.00" required value="{{ old('amount') }}">
                                            </div>
                                        </div><!-- row end -->
                                    </div>

                                    <div class="p-4 border mb-4">
                                        <div class="row">
                                            <div class="col-lg-6 form-group">
                                                <label class="mb-0">@lang('From Currency')<span
                                                        class="text--danger">*</span></label>
                                                <select class="select style--two from_currency" name="from_wallet_id"
                                                    required>
                                                    <option value="">@lang('From Currency')</option>
                                                    @foreach ($user->wallets()->where('balance', '>', 0)->get() as $fromWallet)
                                                        <option value="{{ $fromWallet->id }}"
                                                            data-code="{{ $fromWallet->currency->currency_code }}"
                                                            data-rate="{{ $fromWallet->currency->rate }}"
                                                            data-type="{{ $fromWallet->currency->currency_type }}">
                                                            {{ $fromWallet->currency->currency_code }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-6 form-group">
                                                <label class="mb-0">@lang('To Currency')<span
                                                        class="text--danger">*</span></label>
                                                <select class="select style--two to_currency" name="to_wallet_id"
                                                    required>
                                                    <option value="">@lang('To Currency')</option>
                                                    @foreach ($user->wallets()->get() as $toWallet)
                                                        <option value="{{ $toWallet->id }}"
                                                            data-code="{{ $toWallet->currency->currency_code }}"
                                                            data-rate="{{ $toWallet->currency->rate }}"
                                                            data-type="{{ $toWallet->currency->currency_type }}">
                                                            {{ $toWallet->currency->currency_code }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div><!-- row end -->
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit"
                                    class="btn btn-md btn--base mt-4 exchange w-100">@lang('Exchange')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>






    <div class="modal fade" id="confirm" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">@lang('Exchange Calculation')</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-0">
                    <div class="d-widget border-start-0 shadow-sm">
                        <div class="d-widget__content">
                            <ul class="cmn-list-two text-center mt-4">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong class="from_curr"> </strong>
                                    <strong class="text--base">@lang('TO')</strong>
                                    <strong class="to_curr"></strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="from_curr_val"></span>
                                    <strong>---------------------------------------------------</strong>
                                    <span class="to_curr_val"></span>
                                </li>
                            </ul>
                        </div>
                        <div class="d-widget__footer text-center border-0 pb-3">
                            <button type="submit" class="btn btn-md w-100 d-block btn--base req_confirm"
                                form="form">@lang('Confirm')
                                <i class="las la-long-arrow-alt-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    {{-- Deposit Money --}}
    <script>
        'use strict';
        (function($) {

            var wallet = null;

            $('#wallet').on('change', function() {

                if ($(this).find('option:selected').val() == '') {
                    return false
                }

                wallet = $(this);

                var gateways = $(this).find('option:selected').data('gateways')
                var sym = $(this).find('option:selected').data('sym')
                var code = $(this).find('option:selected').data('code')
                var rate = $(this).find('option:selected').data('rate')

                $('.curr_code').text(code)
                $('.sym').text(sym)
                $('input[name=currency]').val(code)
                $('input[name=currency_id]').val($(this).find('option:selected').data('currency'))

                $('.gateway').removeAttr('disabled')
                $('.gateway').children().remove()
                var html = `<option value="">@lang('Select Gateway')</option>`;

                if (gateways.length > 0) {
                    $.each(gateways, function(i, val) {
                        html +=
                            ` <option data-max="${val.max_amount}" data-min="${val.min_amount}" data-fixcharge = "${val.fixed_charge}" data-percent="${val.percent_charge}" data-rate="${rate}" value="${val.method_code}">${val.name}</option>`
                    });
                    $('.gateway').append(html)
                    $('.gateway-msg').text('')

                } else {
                    $('.gateway').attr('disabled', true)
                    $('.gateway').append(html)
                    $('.gateway-msg').text('No gateway found with this currency.')
                }

            })

            $('.gateway').on('change', function() {

                if ($('.gateway option:selected').val() == '') {
                    $('.amount').attr('disabled', true)
                    $('.charge').text('0.00')
                    $('.payable').text(parseFloat($('.amount').val()))
                    $('.limit').text('limit : 0.00 USD')
                    return false
                }

                $('.amount').removeAttr('disabled')
                var amount = $('.amount').val() ? parseFloat($('.amount').val()) : 0;
                var code = $(wallet).find('option:selected').data('code')

                var type = $(wallet).find('option:selected').data('type')

                var rate = parseFloat($('.gateway option:selected').data('rate'))
                var min = parseFloat($('.gateway option:selected').data('min'))
                var max = parseFloat($('.gateway option:selected').data('max'))

                min = min / rate;
                max = max / rate;

                var fixed = parseFloat($('.gateway option:selected').data('fixcharge'))
                var pCharge = parseFloat($('.gateway option:selected').data('percent'))
                var percent = (amount * parseFloat($('.gateway option:selected').data('percent'))) / 100

                var totalCharge = fixed + percent
                var totalAmount = amount + totalCharge
                var precesion = 0;

                if (type == 1) {
                    precesion = 2;
                } else {
                    precesion = 8;
                }

                $('.charge').text(totalCharge.toFixed(precesion))
                $('.payable').text(totalAmount.toFixed(precesion))
                $('.limit').text('limit : ' + min.toFixed(precesion) + ' ~ ' + max.toFixed(precesion) + ' ' +
                    code)

                $('.f_charge').text(fixed)
                $('.p_charge').text(pCharge)

            })

            $('.amount').on('keyup', function() {
                var amount = parseFloat($(this).val())

                var type = $(wallet).find('option:selected').data('type')
                var code = $(wallet).find('option:selected').data('code')
                var fixed = parseFloat($('.gateway option:selected').data('fixcharge'))

                var percent = (amount * parseFloat($('.gateway option:selected').data('percent'))) / 100
                var totalCharge = fixed + percent
                var totalAmount = amount + totalCharge
                var precesion = 0;

                if (type == 1) {
                    precesion = 2;
                } else {
                    precesion = 8;
                }

                if (!isNaN(amount)) {
                    $('.show-amount').text(amount.toFixed(precesion))
                    $('.charge').text(totalCharge.toFixed(precesion))
                    $('.payable').text(totalAmount.toFixed(precesion))
                } else {
                    $('.show-amount').text('0.00')
                    $('.charge').text('0.00')
                    $('.payable').text('0.00')

                }
            })

            $('.req_confirm').on('click', function() {
                if ($('.amount').val() == '' || $('.gateway option:selected').val() == '' || $(wallet).find(
                        'option:selected').val() == '') {
                    notify('error', 'All fields are required')
                    return false
                }
                $('#form').submit()
                $(this).attr('disabled', true)
            })

        })(jQuery);
    </script>

    {{-- Currency converter --}}
    <script>
        'use strict';
        (function($) {
            $('.to_currency').on('change', function() {
                var fromCurr = $('.from_currency option:selected').val()
                if ($('.to_currency option:selected').val() == fromCurr) {
                    notify('error', 'Can\'t exchange within same wallet.')
                    $('.exchange').attr('disabled', true);
                } else {
                    $('.exchange').attr('disabled', false);
                }

            })

            $('#form').on('submit', function() {

                var confirmMdoal = $('#confirm');

                if (!confirmMdoal.is(':visible')) {

                    var amount = $('.amount').val();
                    if (amount == '') {
                        notify('error', 'Please provide the amount first.')
                        return false
                    }
                    var fromCurr = $('.from_currency option:selected').data('code')
                    var toCurr = $('.to_currency option:selected').data('code')
                    if (!fromCurr || !toCurr) {
                        notify('error', 'Please select the currencies.')
                        return false
                    }
                    var toCurrType = $('.to_currency option:selected').data('type')
                    var fromCurrRate = parseFloat($('.from_currency option:selected').data('rate'))
                    var baseCurrAmount = amount * fromCurrRate;
                    var toCurrRate = parseFloat($('.to_currency option:selected').data('rate'))

                    if (toCurrType == 1) {
                        var toCurrAmount = (baseCurrAmount / toCurrRate).toFixed(2);
                    } else {
                        var toCurrAmount = (baseCurrAmount / toCurrRate).toFixed(8);
                    }

                    $('#confirm').find('.from_curr').text(fromCurr)
                    $('#confirm').find('.to_curr').text(toCurr)
                    $('#confirm').find('.from_curr_val').text(parseFloat(amount))
                    $('#confirm').find('.to_curr_val').text(toCurrAmount)
                    $('#confirm').modal('show')

                    confirmMdoal.modal('show');
                    return false;
                }

            });

            var old = @json(session()->getOldInput());
            if (old.length != 0) {
                $('select[name=from_wallet_id]').val(old.from_wallet_id);
                $('select[name=to_wallet_id]').val(old.to_wallet_id);
            }

        })(jQuery);
    </script>
@endpush
