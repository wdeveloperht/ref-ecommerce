<?php

namespace Botble\InecoBank\Services\Gateways;

use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Payment\Supports\PaymentHelper;
use Botble\InecoBank\Services\Abstracts\InecoBankPaymentAbstract;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class InecoBankPaymentService extends InecoBankPaymentAbstract
{
    /**
     * @param array $data
     * @return string|null
     */
    public function makePayment(array $data): ?string
    {
        $currency = strtoupper($data['currency']);
        $amount = round((float) $data['amount'], $this->isSupportedDecimals() ? 2 : 0);
        dump($data);

        $queryParams = [
            'type' => INECOBANK_PAYMENT_METHOD_NAME,
            'amount' => $amount,
            'currency' => $currency,
            'order_id' => $data['order_id'],
            'customer_id' => Arr::get($data, 'customer_id'),
            'customer_type' => Arr::get($data, 'customer_type'),
        ];

        if ($cancelUrl = $data['return_url'] ?: PaymentHelper::getCancelURL()) {
            $this->setCancelUrl($cancelUrl);
        }

        $description = Str::limit($data['description'], 50);

        return $this
            ->setReturnUrl($data['callback_url'] . '?' . http_build_query($queryParams))
            ->setCustomer(Arr::get($data, 'address.email') ?: '')
            ->setCurrency($currency)
            ->setDescription($description)
            ->setItem([
                'name' => $description,
                'quantity' => 1,
                'price' => $amount,
                'sku' => null,
                'type' => PAYPAL_PAYMENT_METHOD_NAME,
            ])
            ->createPayment();
    }

    /**
     * @param string $chargeId
     * @param array $data
     * @return string
     */
    public function afterMakePayment(string $chargeId, array $data): string
    {
        try {
            do_action('payment_before_making_api_request', INECOBANK_PAYMENT_METHOD_NAME, ['id' => $chargeId]);

            $payment = $this->getPaymentDetails($chargeId);

            do_action('payment_after_api_response', INECOBANK_PAYMENT_METHOD_NAME, ['id' => $chargeId], $payment ? $payment->toArray() : []);

            if ($payment && ($payment->paid || $payment->status == 'succeeded')) {
                $paymentStatus = PaymentStatusEnum::COMPLETED;
            } else {
                $paymentStatus = PaymentStatusEnum::FAILED;
            }
        } catch (Exception) {
            $paymentStatus = PaymentStatusEnum::FAILED;
        }

        do_action(PAYMENT_ACTION_PAYMENT_PROCESSED, [
            'amount' => $data['amount'],
            'currency' => $data['currency'],
            'charge_id' => $chargeId,
            'order_id' => (array) $data['order_id'],
            'customer_id' => Arr::get($data, 'customer_id'),
            'customer_type' => Arr::get($data, 'customer_type'),
            'payment_channel' => INECOBANK_PAYMENT_METHOD_NAME,
            'status' => $paymentStatus,
        ]);

        return $chargeId;
    }

    /**
     * @return bool|string|null
     */
    public function createPayment(): bool|string|null
    {
        $checkoutUrl = '';
        $paymentId = null;
        $orderRequest = [];
        try {
            do_action('payment_before_making_api_request', INECOBANK_PAYMENT_METHOD_NAME, $orderRequest);

            // Call API with your client and get a response for your call
            $response = ''; // $this->api TODO  HT getPaymentLink

            do_action('payment_after_api_response', INECOBANK_PAYMENT_METHOD_NAME, (array)$orderRequest, (array)$response);

            if ($response && $response->statusCode == 201) {
                // @phpstan-ignore-next-line
                $paymentId = $response->result->id;

                // @phpstan-ignore-next-line
                foreach ($response->result->links as $link) {
                    if ($link->rel == 'approve') {
                        $checkoutUrl = $link->href;
                    }
                }
            }
        } catch (Exception $exception) {
            do_action('payment_after_api_response', INECOBANK_PAYMENT_METHOD_NAME, (array)$exception);

            $this->setErrorMessageAndLogging($exception, 1);

            return false;
        }

        if ($checkoutUrl && $paymentId) {
            session(['inecobank_payment_id' => $paymentId]);

            return $checkoutUrl;
        }

        session()->forget('inecobank_payment_id');

        return null;
    }
}
