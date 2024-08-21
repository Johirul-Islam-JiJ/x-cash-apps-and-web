@php
    $content = @getContent('service.content', true)->data_values;
    $elements = @getContent('service.element', orderById: true);
@endphp

<section class="pt-100 pb-150 service-section">
    <div class="section-wave-img opacity50">
        <img src="{{ getImage('assets/images/frontend/service/' . $content->background_image, '1920x1080') }}"
            alt="@lang('image')">
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-8">
                <div class="text-center">
                    <span class="section-subtitle border-left">Service</span>
                    <div class="section-header wow fadeInUp" data-wow-duration="0.3" data-wow-delay="0.3s">
                        <h2 class="section-title">{{ __(@$content->heading) }}</h2>
                        <p class="section-subtitle mt-3 opacity-75">{{ __(@$content->subheading) }}</p>
                    </div>
                </div>
            </div>
        </div><!-- row end -->
        <div class="row gy-4">
            @foreach ($elements as $element)
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-duration="0.3" data-wow-delay="0.1s">
                    <div class="service-card">
                        <div class="service-card__icon">
                            @php
                                echo @$element->data_values->service_icon;
                            @endphp
                        </div>
                        <div class="service-card__content">
                            <h3 class="title">{{ @$element->data_values->title }}</h3>
                            <p class="desc">{{ __(@$element->data_values->description) }}</p>
                        </div>
                    </div><!-- service-card end -->
                </div>
            @endforeach
        </div>
    </div>
</section>
