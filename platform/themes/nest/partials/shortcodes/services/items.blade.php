@if ($keys->isNotEmpty())
    @php $shortcode = $shortcode ?? null;@endphp
    <section class="banners pt-60">
        <div class="container">
            <div class="row justify-content-center">
                 @foreach ($keys as $key)
                    <div class="col-lg-4 col-md-6">
                        {!! display_service($key, '', $loop, $shortcode) !!}
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
