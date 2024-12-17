<?php

namespace Botble\Services\Supports;

use Botble\Services\Models\Service;
use Botble\Base\Enums\BaseStatusEnum;
use Illuminate\Support\Collection;

class ServiceManager
{
    protected Collection $data;

    protected bool $loaded = false;

    protected array $locations = [];

    public function __construct()
    {
        $this->locations = [
            'not_set' => trans('plugins/services::services.not_set'),
        ];

        $this->data = collect();
    }

    public function display(string $location, array $attributes = []): string
    {
        $this->load();

        $data = $this
            ->filterExpired($this->data)
            ->sortBy('order');

        if ($data->isNotEmpty()) {
            $data = $data->random(1);
        }

        return view('plugins/services::partials.service-display', compact('data', 'attributes'))->render();
    }

    public function load(bool $force = false, array $with = []): self
    {
        if (!$this->loaded || $force) {
            $this->data = $this->read($with);
            $this->loaded = true;
        }

        return $this;
    }

    protected function read(array $with): Collection
    {
        return Service::query()->with($with)->get();
    }

    public function displayService(?string $key, array $attributes = []): ?string
    {
        if (!$key) {
            return null;
        }

        $this->load();

        $service = $this
            ->filterExpired($this->data)
            ->where('key', $key)
            ->first();

        if (!$service) {
            return null;
        }

        $data = [$service];

        if (!isset($attributes['style'])) {
            $attributes['style'] = 'text-align: center;';
        }

        return view('plugins/services::partials.service-display', compact('data', 'attributes'))->render();
    }

    public function getData(bool $isLoad = false, bool $isNotExpired = false): Collection
    {
        if ($isLoad) {
            $this->load();
        }

        if ($isNotExpired) {
            return $this->filterExpired($this->data);
        }

        return $this->data;
    }

//    public function registerLocation(string $key, string $name): self
//    {
//        $this->locations[$key] = $name;
//
//        return $this;
//    }
//
//    public function getLocations(): array
//    {
//        return apply_filters('ads_locations', $this->locations);
//    }

    public function getService(string $key): ?Service
    {
        if (!$key) {
            return null;
        }

        $service = $this->getData(true)->firstWhere('key', $key);

        if (!$service || !$service->image) {
            return null;
        }

        return $service;
    }

    protected function filterExpired(Collection $data): Collection
    {
        return $data
            ->where('status', BaseStatusEnum::PUBLISHED);
    }
}
