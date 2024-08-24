@php
    $content = @getContent('testimonial.content', true)->data_values;
    $elements = @getContent('testimonial.element', orderById: true);
@endphp

<section class="pt-100 pb-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="section-header">
                    <span class="section-subtitle border-left wow fadeInUp" data-wow-duration="0.3"
                        data-wow-delay="0.1s">{{ __(@$content->title) }}
                    </span>
                    <h2 class="section-title wow fadeInUp" data-wow-duration="0.3" data-wow-delay="0.3s">
                        {{ __(@$content->heading) }}
                    </h2>
                    <p class="mt-3 wow fadeInUp" data-wow-duration="0.3" data-wow-delay="0.5s">
                        {{ __(@$content->subheading) }}
                    </p>
                </div>
            </div>
        </div>

        <!-- row end -->
        <div class="row">
            <div class="col-lg-12">
                <div class="testimonial-sliderr">
                    @foreach ($elements as $element)
                        <div class="testimoinal-item">
                            <p class="testimoinal-item-desc">{{ __(@$element->data_values->quote) }}</p>
                            <div class="testimoinal-item-auth">
                                <div class="testimoinal-item-image">
                                    <img src="{{ getImage('assets/images/frontend/testimonial/' . @$element->data_values->author_image) }}"
                                        alt="@lang('img')">
                                </div>

                                <div class="testimoinal-item-content">
                                    <h3 class="name">{{ __(@$element->data_values->author_name) }}</h3>
                                    <span class="desi">{{ __(@$element->data_values->designation) }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>


@push('script')
    <script>
        $('.testimonial-sliderr').slick({
            autoplay: true,
            autoplaySpeed: 2000,
            dots: false,
            infinite: true,
            speed: 300,
            slidesToShow: 4,
            arrows: false,
            slidesToScroll: 1,
            responsive: [{
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: 3,
                    }
                },
                {
                    breakpoint: 991,
                    settings: {
                        slidesToShow: 3,
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 2,
                    }
                },
                {
                    breakpoint: 575,
                    settings: {
                        slidesToShow: 1,
                    }
                }
            ]
        });
    </script>
@endpush
