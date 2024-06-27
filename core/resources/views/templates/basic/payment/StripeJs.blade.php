@extends($activeTemplate.'layouts.'.strtolower(userGuard()['type']).'_master')

@php
    if (userGuard()['type'] == 'AGENT' || userGuard()['type'] == 'MERCHANT'){
        $class = 'mt-5 ';
    } 
@endphp

@section('content')
<div class="{{ @$class }} justify-content-center d-flex flex-wrap">
    <div class="col-xl-6 col-lg-6 col-md-8">
        <div class="d-widget shadow-sm">
            <div class="d-widget__header text-center">
                <h6>{{__($deposit->gateway->name)}}</h6>
            </div>
            <div class="d-widget__content">
                <ul class="cmn-list-two text-center mt-4">
                    <li>
                        @lang('Please Pay'):
                        <strong>{{showAmount($deposit->final_amo,getCurrency($deposit->method_currency))}} {{__($deposit->method_currency)}} </strong>
                    </li>
                    <li>
                        @lang('To Get'):
                        <strong>{{showAmount($deposit->amount,$deposit->convertedCurr)}} {{$deposit->convertedCurrency->currency_code}}</strong>
                    </li>

                </ul>
            </div>
            <div class="d-widget__footer text-center border-0 pb-3">
                <form action="{{$data->url}}" method="{{$data->method}}">
                    <script src="{{$data->src}}"
                        class="stripe-button"
                        @foreach($data->val as $key=> $value)
                        data-{{$key}}="{{$value}}"
                        @endforeach
                    >
                    </script>
                 </form>
            </div>
        </div><!-- d-widget end -->
    </div>
</div>
@endsection

@push('script-lib')
    <script src="https://js.stripe.com/v3/"></script>
@endpush

@push('script')
    <script>
        (function ($) {
            "use strict";
            $('button[type="submit"]').removeClass('stripe-button-el').addClass("btn btn--base w-100");
        })(jQuery);
    </script>
@endpush


