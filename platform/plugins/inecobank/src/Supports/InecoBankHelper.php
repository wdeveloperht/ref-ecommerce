<?php

namespace Botble\InecoBank\Supports;

class InecoBankHelper
{
    /**
     * Determine which currency need to multiply 100ths
     *
     * For example:
     * If you use USD currency and want to charge customer for $10, you must send to Inecobank server the amount = 1000 cents.
     * Because the InecoBank server get the amount in cents.
     * Refer: https://stripe.com/docs/api#charge_object-amount
     *
     * But for some currency, you don't need to multiply by 100ths because their smallest currency unit is 1.
     *
     * @param string $currency
     *
     * @refer: https://support.stripe.com/questions/which-zero-decimal-currencies-does-stripe-support
     * @return int
     */
    public static function getInecoBankCurrencyMultiplier($currency = '')
    {
        $currency = strtoupper($currency);

        // default
        if (empty($currency)) {
            return 100;
        }

        // these currencies no need to multiply by 100ths
        $zeroDecimalCurrencies = [
            'BIF',
            'CLP',
            'DJF',
            'GNF',
            'JPY',
            'KMF',
            'KRW',
            'MGA',
            'PYG',
            'RWF',
            'VND',
            'VUV',
            'XAF',
            'XOF',
            'XPF',
        ];

        return in_array(strtoupper($currency), $zeroDecimalCurrencies) ? 1 : 100;
    }

    /**
     * @param string $key
     * @param $type
     * @param $default
     * @return string|array|null
     */
    public static function getPaymentSetting(string $key, $type = null, $default = null): string|array|null
    {
        return setting(self::getPaymentSettingKey($key, $type), $default);
    }

    /**
     * @param string $key
     * @param string|null $type
     * @return string
     */
    public static function getPaymentSettingKey(string $key, ?string $type = null): string
    {
        $key = $type ? "payment_{$type}_{$key}" : "payment_$key";

        return apply_filters('payment_setting_key', $key);
    }

}
