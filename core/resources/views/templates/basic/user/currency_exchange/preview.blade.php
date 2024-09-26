@extends($activeTemplate . 'layouts.user_master')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card style--two">
                <div class="card-header d-flex flex-wrap align-items-center justify-content-center">
                    <div class="bank-icon me-2">
                        <i class="las la-university"></i>
                    </div>
                    <h4 class="fw-normal">{{ __($pageTitle) }}</h4>
                </div>
               
                <div class="card-body p-4">
                    <form action="{{ route(strtolower(userGuard()['type']) . '.currency.exchange.manual.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="text-center mb-3">@lang('Deposit Information')</h5>
                                <div class="deposit-info">
                                    <p class="text-center mt-2">@lang('You have requested') <b class="text-success">{{ showAmount($data['amount']) }} {{ __($general->cur_text) }}</b>, @lang('Please pay')
                                        <b class="text-success">{{ showAmount($data['final_amo']) . ' ' . $data['method_currency'] }}</b> @lang('for successful payment')
                                    </p>
                                    <h6 class="text-center mb-4">@lang('Please follow the instruction below')</h6>
                                    <p class="my-4 text-center">@php echo $data->gateway->description @endphp</p>
                                </div>

                                <x-viser-form identifier="id" identifierValue="{{ $gateway->form_id }}" />
                            </div>

                            <div class="col-md-6">
                                <h5 class="text-center mb-3">@lang('Withdrawal Information')</h5>
                                <div class="withdraw-info">
                                    <div class="d-widget shadow-sm">
                                        <div class="d-widget__header text-center">
                                            <h6>{{__($withdraw->method->name)}}</h6>
                                        </div>
                                        <div class="d-widget__content">
                                            <ul class="cmn-list-two text-center">
                                                <li>
                                                    @lang('Requested Amount '):
                                                    <strong>{{showAmount($withdraw->amount)}} </strong> {{$withdraw->currency}}
                                                </li>
                                                <li>
                                                    @lang('Withdrawal Charge '):
                                                    <strong>{{showAmount($withdraw->charge)}}</strong> {{$withdraw->currency}}
                                                </li>
                                                <li>
                                                    @lang('You will get '): <strong> {{showAmount($withdraw->final_amount)}}</strong> {{$withdraw->currency}}
                                                </li>
                                                <li>
                                                    @lang('Money Will be send to your withdraw methods. '): <strong> if not selected, select it as soon as possible.</strong>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($general->otp_verification && ($general->en || $general->sn || $withdraw->user->ts))
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="p-4 border mt-4">
                                        <div class="row">
                                            <div class="col-lg-12 form-group">
                                                @include($activeTemplate.'partials.otp_select')
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn--base w-100">@lang('Confirm Deposit and Withdrawal')</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection