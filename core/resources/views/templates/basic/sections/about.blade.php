@php
    $content = @getContent('about.content', true)->data_values;
    $elements = @getContent('about.element', orderById:true);
@endphp

<!-- {{ getImage('assets/images/frontend/about/' .@$content->background_image, '992x692') }} -->

<!-- about section start -->
<section class="pt-100 pb-100 about-section">
    <div class="container">
        <div class="row justify-content-between align-items-center">
            <div class="col-xl-5">
                <div class="about-thumb">
                    <img src="{{ asset('assets/global/images/aabout.png') }}"
                        alt="@lang('image')" class="wow fadeInUp" data-wow-duration="0.3" data-wow-delay="0.3s">
                </div>
            </div>
            <div class="col-xl-6 mt-xl-0 mt-5">
                <span class="section-subtitle border-left">{{ __(@$content->title) }}</span>
                <h2 class="section-title">{{ @$content->heading }}</h2>
                <p class="section-subtitle">{{ @$content->short_details }}</p>
                <a href="{{ @$content->button_link }}" class="btn btn--base mt-5">{{ __(@$content->button_name) }}</a>
            </div>
        </div><!-- row end -->
    </div>
</section>
<!-- about section end -->
