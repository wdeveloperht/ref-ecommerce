@if (is_plugin_active('gallery') && count($sliders) > 0 &&
    $sliders->loadMissing('metadata') && $slider->loadMissing('metadata'))
    @php
        $style = $slider->getMetaData('gallery_style', true);
    @endphp
    <section class="style-2 position-relative mb-50" @if ($shortcode->cover_image) style="background-image: url({{ RvMedia::getImageUrl($shortcode->cover_image) }}) !important;" @endif>
        <div class="container">
            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <ul class="lg-gallery">
                        @foreach($sliders as $slider)
                            @php
                                $tabletImage = $slider->getMetaData('tablet_image', true) ?: $slider->image;
                                $mobileImage = $slider->getMetaData('mobile_image', true) ?: $tabletImage;
                            @endphp
                        <li class="col-xs-6 col-sm-4 col-md-3"
                            data-responsive="{{ RvMedia::getImageUrl($slider->image, null, false, RvMedia::getDefaultImage()) }} 1200, {{ RvMedia::getImageUrl($tabletImage, null, false, RvMedia::getDefaultImage()) }} 768, {{ RvMedia::getImageUrl($mobileImage, null, false, RvMedia::getDefaultImage()) }} 767"
                            data-src="{{ RvMedia::getImageUrl($slider->image, null, false, RvMedia::getDefaultImage()) }}"
                            data-sub-html="<h4>{{ $slider->title }}</h4><p>{{ $slider->description }}</p>"
                           >
                            <a href="">
                                <img class="img-responsive" src="{{ RvMedia::getImageUrl($slider->image, null, false, RvMedia::getDefaultImage()) }}" alt="{{ $slider->title }}">
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>
@endif
