@php
    $content = @getContent('feature.content', true)->data_values;
    $elements = @getContent('feature.element', orderById:true);
@endphp

<section class="feature-section">

    <div class="feature-top pt-100">
        <div class="container">
        <div class="feature-top-wrapper">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-4">
                    <div class="feature-thumb">
                        <!-- <img src="{{ getImage('assets/images/frontend/feature/' . @$content->image, '768x888') }}"
                            alt="@lang('image')"
                        > -->

                        <img src="{{ asset('assets/global/images/bank.png') }}" alt="">
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="feature-content">
                        <h4 class="text-white title">{{ __(@$content->heading) }}</h4>
                        <p class="text">{{ __(@$content->subheading) }}</p>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>

    <div class="feature-bottom pt-100 pb-100">
        <div class="container">
            <div class="row gy-4">
                @foreach ($elements as $element)
                    <div class="col-lg-4 col-md-6">
                        <div class="feature-card">
                            <div class="feature-card-icon">
                                <img src="{{ getImage('assets/images/frontend/feature/' .@$element->data_values->icon, '65x65') }}"
                                    alt="image">
                            </div>
                            <div class="feature-card-content">
                                <h3 class="title">{{ __(@$element->data_values->title) }}</h3>
                                <p class="desc">{{ @$element->data_values->short_details }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

</section>
