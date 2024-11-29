<?php

namespace Botble\InecoBank\Http\Controllers;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Payment\Models\Payment;
use Botble\Payment\Supports\PaymentHelper;
use Botble\InecoBank\Http\Requests\InecoBankPaymentCallbackRequest;
use Botble\InecoBank\Services\Gateways\InecoBankPaymentService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class InecoBankController extends BaseController
{
    public function getCallback(
        InecoBankPaymentCallbackRequest $request,
        InecoBankPaymentService         $inecoBankPaymentService,
        BaseHttpResponse                $response
    )
    {
        try {
            $status = $inecoBankPaymentService->getPaymentStatus($request);

            if (! $status) {
                return $response
                    ->setError()
                    ->setNextUrl(PaymentHelper::getCancelURL())
                    ->withInput()
                    ->setMessage(__('Payment failed!'));
            }

            $inecoBankPaymentService->afterMakePayment($request->input());

            return $response
                ->setNextUrl(PaymentHelper::getRedirectURL())
                ->setMessage(__('Checkout successfully!'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setNextUrl(PaymentHelper::getCancelURL())
                ->withInput()
                ->setMessage($exception->getMessage() ?: __('Payment failed!'));
        }
    }

    public function error(BaseHttpResponse $response)
    {
        return $response
            ->setError()
            ->setNextUrl(PaymentHelper::getCancelURL())
            ->withInput()
            ->setMessage(__('Payment failed!'));
    }
}
