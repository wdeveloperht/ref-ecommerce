@if (setting('payment_inecobank_status') == 1)
    <x-plugins-payment::payment-method
        :name="INECOBANK_PAYMENT_METHOD_NAME"
        paymentName="InecoBank"
        :supportedCurrencies="(new Botble\InecoBank\Services\Gateways\InecoBankPaymentService)->supportedCurrencyCodes()"
    >
        @if (false && get_payment_setting('payment_mode', INECOBANK_PAYMENT_METHOD_NAME, 'inecobank_test') == 'inecobank_test')
            <div class="card-checkout" style="max-width: 350px">
                <div class="form-group mt-3 mb-3">
                    <div class="inecobank-card-wrapper"></div>
                </div>

                <div @class(['form-group mb-3', 'has-error' => $errors->has('number') || $errors->has('expiry')])>
                    <div class="row">
                        <div class="col-sm-8">
                            <input
                                class="form-control"
                                id="inecobank-number"
                                data-inecobank="number"
                                type="text"
                                placeholder="{{ trans('plugins/payment::payment.card_number') }}"
                                autocomplete="off"
                            >
                        </div>
                        <div class="col-sm-4">
                            <input
                                class="form-control"
                                id="inecobank-exp"
                                data-inecobank="exp"
                                type="text"
                                placeholder="{{ trans('plugins/payment::payment.mm_yy') }}"
                                autocomplete="off"
                            >
                        </div>
                    </div>
                </div>
                <div @class(['form-group mb-3', 'has-error' => $errors->has('name') || $errors->has('cvc')])>
                    <div class="row">
                        <div class="col-sm-8">
                            <input
                                class="form-control"
                                id="inecobank-name"
                                data-inecobank="name"
                                type="text"
                                placeholder="{{ trans('plugins/payment::payment.full_name') }}"
                                autocomplete="off"
                            >
                        </div>
                        <div class="col-sm-4">
                            <input
                                class="form-control"
                                id="inecobank-cvc"
                                data-inecobank="cvc"
                                type="text"
                                placeholder="{{ trans('plugins/payment::payment.cvc') }}"
                                autocomplete="off"
                            >
                        </div>
                    </div>
                </div>
            </div>
            <div id="payment-inecobank-key" data-value="{{ get_payment_setting('client_id', INECOBANK_PAYMENT_METHOD_NAME) }}"></div>
        @endif
    </x-plugins-payment::payment-method>
@endif
