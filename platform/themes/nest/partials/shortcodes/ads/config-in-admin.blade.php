@for ($i = 1; $i <= ($total ?? 5); $i++)
    <div class="mb-3">
        <label class="form-label">Ad {{ $i }}</label>
        <select name="ads_{{ $i }}" class="form-select">
            <option value="">{{ __('-- select --') }}</option>
            @foreach($ads as $ad)
                <option value="{{ $ad->key }}" @if ($ad->key == Arr::get($attributes, 'ads_' . $i)) selected @endif>{{ $ad->name }}</option>
            @endforeach
        </select>
    </div>
@endfor

<div class="mb-3">
    <label class="form-label">{{ __('Style') }}</label>
    <select name="style" class="form-select">
        <option value="">{{ __('Default') }}</option>
        <option value="style-2" @if (Arr::get($attributes, 'style') == 'style-2') selected @endif>{{ __('Style 2') }}</option>
        <option value="style-4" @if (Arr::get($attributes, 'style') == 'style-4') selected @endif>{{ __('Style 4') }}</option>
        <option value="style-5" @if (Arr::get($attributes, 'style') == 'style-5') selected @endif>{{ __('Style 5') }}</option>
    </select>
</div>

<x-core::form.color-picker name="text_color" :label="__('Text Color')" :value="$attributes['text_color'] ?? theme_option('color_heading', '#253D4E')" />
