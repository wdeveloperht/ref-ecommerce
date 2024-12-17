@for ($i = 1; $i <= ($total ?? 5); $i++)
    <div class="mb-3">
        <label class="form-label">{{ __('Service') }} {{ $i }}</label>
        <select name="service_{{ $i }}" class="form-select">
            <option value="">{{ __('-- select --') }}</option>
            @foreach($services as $service)
                <option value="{{ $service->key }}" @if ($service->key == Arr::get($attributes, 'service_' . $i)) selected @endif>
                    {{ $service->name }}
                </option>
            @endforeach
        </select>
    </div>
@endfor
<div class="mb-3">
    <label class="form-label">{{ __('Style') }}</label>
    <select name="style" class="form-select">
        <option value="">{{ __('Default') }}</option>
        <option value="with-subtitle" @if (Arr::get($attributes, 'style') == 'with-subtitle') selected @endif>{{ __('With Subtitle') }}</option>
        <option value="with-description" @if (Arr::get($attributes, 'style') == 'with-description') selected @endif>{{ __('With Description') }}</option>
       1
    </select>
</div>
<x-core::form.color-picker name="text_color" :label="__('Text Color')" :value="$attributes['text_color'] ?? theme_option('color_heading', '#253D4E')" />
