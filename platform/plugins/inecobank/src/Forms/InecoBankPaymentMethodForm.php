<?php

namespace Botble\InecoBank\Forms;

use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Payment\Forms\PaymentMethodForm;

class InecoBankPaymentMethodForm extends PaymentMethodForm
{
    /**
     * @return void
     */
    public function setup(): void
    {
        parent::setup();

        $this
            ->paymentId(INECOBANK_PAYMENT_METHOD_NAME)
            ->paymentName('Ineco Bank')
            ->paymentDescription(trans('plugins/payment::payment.inecobank_description'))
            ->paymentLogo(url('vendor/core/plugins/inecobank/images/inecobank.svg'))
            ->paymentUrl('https://inecobank.am')
            ->paymentInstructions(view('plugins/inecobank::instructions')->render())
            ->add(
                'payment_' . INECOBANK_PAYMENT_METHOD_NAME . '_payment_mode',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/payment::payment.' . INECOBANK_PAYMENT_METHOD_NAME . '.payment_mode'))
                    ->choices([
                        'inecobank_test' => 'Test',
                        'inecobank_live' => 'Live',
                    ])
                    ->selected(get_payment_setting(
                        'payment_mode',
                        INECOBANK_PAYMENT_METHOD_NAME,
                        'inecobank_test',
                    ))
            )
            ->add(
                'payment_' . INECOBANK_PAYMENT_METHOD_NAME . '_user_name',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/payment::payment.' . INECOBANK_PAYMENT_METHOD_NAME . '.user_name'))
                    ->value(get_payment_setting('user_name', INECOBANK_PAYMENT_METHOD_NAME))
                    ->placeholder('')
                    ->attributes(['data-counter' => 100])
            )
            ->add(
                'payment_' . INECOBANK_PAYMENT_METHOD_NAME . '_password',
                TextField::class, // 'password',
                TextFieldOption::make()
                    ->label(trans('plugins/payment::payment.' . INECOBANK_PAYMENT_METHOD_NAME . '.password'))
                    ->value(get_payment_setting('secret', INECOBANK_PAYMENT_METHOD_NAME))
                    ->placeholder('*************')
                    ->attributes(['data-counter' => 100])
            );
    }
}
