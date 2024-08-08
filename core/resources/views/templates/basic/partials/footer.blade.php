@php
    $footer = @getContent('footer.content', true)->data_values;
    $contact = @getContent('contact_us.content', true)->data_values;
    $policies = @getContent('policy_pages.element', orderById:true);
@endphp

<footer class="footer">
    <div class="footer__top">
        <div class="widget-area">
            <div class="container">
                <div class="row gy-5">
                    <div class="col-lg-3">
                        <div class="footer-widget">
                            <a href="{{ route('home') }}" class="footer-logo">
                                <img src="{{getImage(getFilePath('logoIcon') .'/dark_logo.png')}}" alt="image">
                            </a>
                            <p class=" mt-4">{{ __(@$footer->short_details) }}</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 ps-lg-4">
                        <div class="footer-widget">
                            <h6 class="footer-widget__title ">@lang('Quick Menu')</h6>
                            <ul class="footer-link-list">
                                @php
                                    $pages = App\Models\Page::where('tempname', $activeTemplate)->where('is_default', 0)->get();
                                @endphp
                                @foreach ($pages as $k => $data)
                                    <li><a href="{{ route('pages', [$data->slug]) }}">{{ __($data->name) }}</a></li>
                                @endforeach
                                <li><a href="{{ route('blogs') }}">@lang('Announcement')</a></li>
                                <li><a href="{{ route('api.documentation') }}">@lang('Developer')</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 ps-lg-4">
                        <div class="footer-widget">
                            <h6 class="footer-widget__title ">@lang('Get Started')</h6>
                            <ul class="footer-link-list">
                                <li><a href="{{ route('user.login') }}">@lang('Login as User')</a></li>
                                <li><a href="{{ route('agent.login') }}">@lang('Login as Agent')</a></li>
                                <li><a href="{{ route('merchant.login') }}">@lang('Login as Merchant')</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 ps-lg-4">
                        <div class="footer-widget">
                            <h6 class="footer-widget__title ">@lang('Useful links')</h6>
                            <ul class="footer-link-list">
                                @foreach ($policies as $policy)
                                    <li>
                                        <a href="{{ route('policy.pages', [slug($policy->data_values->title), $policy->id]) }}">
                                            {{ $policy->data_values->title }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-contact-area">
            <div class="container">
                <div class="row justify-content-center align-items-center mb-5 footer-contact-wrapper">
                    <div class="col-md-4 col-sm-6 footer-contact-item">
                        <div class="footer-contact-card">
                            <div class="icon">
                                <i class="las la-envelope"></i>
                            </div>
                            <div class="content">
                                <h5><a href="mailto:{{ @$contact->email_address }}">{{ @$contact->email_address }}</a>
                                </h5>
                                <span class="caption font-size--14px">@lang('Mail Address')</span>
                            </div>
                        </div><!-- footer-contact-card end -->
                    </div>
                    <div class="col-md-4 col-sm-6 footer-contact-item">
                        <div class="footer-contact-card">
                            <div class="icon">
                                <i class="las la-phone-volume"></i>
                            </div>
                            <div class="content">
                                <h5><a href="tel:{{ @$contact->contact_number }}">{{ @$contact->contact_number }}</a>
                                </h5>
                                <span class="caption font-size--14px">@lang('Call Us')</span>
                            </div>
                        </div><!-- footer-contact-card end -->
                    </div>
                    <div class="col-md-4 col-sm-6 footer-contact-item">
                        <div class="footer-contact-card">
                            <div class="icon">
                                <i class="las la-map-marked-alt"></i>
                            </div>
                            <div class="content">
                                <h5 class="">{{ @$contact->address }}</h5>
                                <span class="caption font-size--14px">@lang('Address')</span>
                            </div>
                        </div><!-- footer-contact-card end -->
                    </div>
                </div><!-- row end -->
            </div>
        </div>
    </div>
    <div class="footer__bottom">
        <div class="container">
            <p class="text-center">&copy; {{ date('Y') }} {{ __($general->site_name) }}. @lang('All rights reserved')</p>
        </div>
    </div>
</footer>
