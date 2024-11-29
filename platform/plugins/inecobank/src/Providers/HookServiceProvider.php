<?php

namespace Botble\InecoBank\Providers;

use Botble\Base\Facades\Html;
use Botble\Payment\Enums\PaymentMethodEnum;
use Botble\Payment\Facades\PaymentMethods;
use Botble\InecoBank\Forms\InecoBankPaymentMethodForm;
use Botble\InecoBank\Services\Gateways\InecoBankPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class HookServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot(): void
    {
        add_filter(PAYMENT_FILTER_ADDITIONAL_PAYMENT_METHODS, [$this, 'registerInecoBankMethod'], 1, 2);

        $this->app->booted(function (): void {
            add_filter(PAYMENT_FILTER_AFTER_POST_CHECKOUT, [$this, 'checkoutWithInecoBank'], 1, 2);
        });

        add_filter(PAYMENT_METHODS_SETTINGS_PAGE, [$this, 'addPaymentSettings'], 3);

        add_filter(BASE_FILTER_ENUM_ARRAY, function ($values, $class) {
            if ($class == PaymentMethodEnum::class) {
                $values['INECOBANK'] = INECOBANK_PAYMENT_METHOD_NAME;
            }

            return $values;
        }, 1, 2);

        add_filter(BASE_FILTER_ENUM_LABEL, function ($value, $class) {
            if ($class == PaymentMethodEnum::class && $value == INECOBANK_PAYMENT_METHOD_NAME) {
                $value = 'InecoBank';
            }

            return $value;
        }, 1, 2);

        add_filter(BASE_FILTER_ENUM_HTML, function ($value, $class) {
            if ($class == PaymentMethodEnum::class && $value == INECOBANK_PAYMENT_METHOD_NAME) {
                $value = Html::tag(
                    'span',
                    PaymentMethodEnum::getLabel($value),
                    ['class' => 'label-success status-label']
                )
                    ->toHtml();
            }

            return $value;
        }, 1, 2);

        add_filter(PAYMENT_FILTER_GET_SERVICE_CLASS, function ($data, $value) {
            if ($value == INECOBANK_PAYMENT_METHOD_NAME) {
                $data = InecoBankPaymentService::class;
            }

            return $data;
        }, 1, 2);

        add_filter(PAYMENT_FILTER_PAYMENT_INFO_DETAIL, function ($data, $payment) {
            if ($payment->payment_channel == INECOBANK_PAYMENT_METHOD_NAME) {
                $paymentDetail = (new InecoBankPaymentService())->getPaymentDetails($payment->charge_id);

                $data = view('plugins/inecobank::detail', ['payment' => $paymentDetail])->render();
            }

            return $data;
        }, 1, 2);
    }

    /**
     * @param string|null $settings
     * @return string
     */
    public function addPaymentSettings(?string $settings): string
    {
        return $settings . InecoBankPaymentMethodForm::create()->renderForm();
    }

    /**
     * @param string|null $html
     * @param array $data
     * @return string
     */
    public function registerInecoBankMethod(?string $html, array $data): string
    {
        PaymentMethods::method(INECOBANK_PAYMENT_METHOD_NAME, [
            'html' => view('plugins/inecobank::methods', $data)->render(),
        ]);

        return $html;
    }

    /**
     * @param array $data
     * @param Request $request
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function checkoutWithInecoBank(array $data, Request $request): array
    {
        if ($data['type'] !== INECOBANK_PAYMENT_METHOD_NAME) {
            return $data;
        }

        $paymentData = apply_filters(PAYMENT_FILTER_PAYMENT_DATA, [], $request);

        $currentCurrency = get_application_currency();

        $inecoBankPaymentService = $this->app->make(InecoBankPaymentService::class);
// TODO HT not need
        $supportedCurrencies = $inecoBankPaymentService->supportedCurrencyCodes();

        if (!in_array($paymentData['currency'], $supportedCurrencies) && strtoupper($currentCurrency->title) !== 'USD') {
            $currencyModel = $currentCurrency->replicate();

            $supportedCurrency = $currencyModel->query()->where('title', 'USD')->first();

            if ($supportedCurrency) {
                $paymentData['currency'] = strtoupper($supportedCurrency->title);
                if ($currentCurrency->is_default) {
                    $paymentData['amount'] = $paymentData['amount'] * $supportedCurrency->exchange_rate;
                } else {
                    $paymentData['amount'] = format_price(
                        $paymentData['amount'] / $currentCurrency->exchange_rate,
                        $currentCurrency,
                        true
                    );
                }
            }
        }

        if (!in_array($paymentData['currency'], $supportedCurrencies)) {
            $data['error'] = true;
            $data['message'] = __(
                ":name doesn't support :currency. List of currencies supported by :name: :currencies.",
                [
                    'name' => 'InecoBank',
                    'currency' => $paymentData['currency'],
                    'currencies' => implode(', ', $supportedCurrencies),
                ]
            );

            return $data;
        }

        if (!$request->input('callback_url')) {
            $paymentData['callback_url'] = route('payments.inecobank.status');
        }

        $checkoutUrl = $inecoBankPaymentService->execute($paymentData);

        dd($checkoutUrl);

        if ($inecoBankPaymentService->getErrorMessage()) {
            $data['error'] = true;
            $data['message'] = $inecoBankPaymentService->getErrorMessage();
        } elseif ($checkoutUrl) {
            $data['checkoutUrl'] = $checkoutUrl;
        }

        return $data;
    }
}
