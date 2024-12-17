<?php

namespace Botble\Services\Http\Controllers;

use Botble\Services\Forms\ServiceForm;
use Botble\Services\Http\Requests\ServiceRequest;
use Botble\Services\Models\Service;
use Botble\Services\Tables\ServiceTable;
use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Facades\PageTitle;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Illuminate\Http\Request;

class ServiceController extends BaseController
{
    public function index(ServiceTable $table)
    {
        PageTitle::setTitle(trans('plugins/services::services.name'));

        return $table->renderTable();
    }

    public function create()
    {
        PageTitle::setTitle(trans('plugins/services::services.create'));

        return ServiceForm::create()->renderForm();
    }

    public function store(ServiceRequest $request, BaseHttpResponse $response)
    {
        $form = ServiceForm::create()->setRequest($request);
        $form->save();

        return $response
            ->setPreviousUrl(route('services.index'))
            ->setNextUrl(route('services.edit', $form->getModel()->id))
            ->withCreatedSuccessMessage();
    }

    public function edit(Service $service, Request $request)
    {
        event(new BeforeEditContentEvent($request, $service));

        PageTitle::setTitle(trans('core/base::forms.edit_item', ['name' => $service->name]));

        return ServiceForm::createFromModel($service)->renderForm();
    }

    public function update(Service $service, ServiceRequest $request, BaseHttpResponse $response)
    {
        ServiceForm::createFromModel($service)
            ->setRequest($request)
            ->save();

        return $response
            ->setPreviousUrl(route('services.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(Service $service)
    {
        return DeleteResourceAction::make($service);
    }
}
