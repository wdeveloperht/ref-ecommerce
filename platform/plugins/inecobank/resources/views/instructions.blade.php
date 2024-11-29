@php
/*
<ol>
    <li>
        <p>
            <a href="https://inecobank.am" target="_blank">
                {{ trans('plugins/payment::payment.service_registration', ['name' => 'InecoBank']) }}
            </a>
        </p>
    </li>
    <li>
        <p>{{ trans('plugins/payment::payment.inecobank_after_service_registration_msg', ['name' => 'InecoBank']) }}</p>
    </li>
    <li>
        <p>{{ trans('plugins/payment::payment.inecobank_enter_client_id_and_secret') }}</p>
    </li>
</ol>

<h4>{{ trans('plugins/inecobank::inecobank.webhook_setup_guide.title') }}</h4>

<p>{{ trans('plugins/inecobank::inecobank.webhook_setup_guide.description') }}</p>

<ol>
    <li>
        <p><strong>{{ trans('plugins/inecobank::inecobank.webhook_setup_guide.step_1_label') }}:</strong> {!! BaseHelper::clean(trans('plugins/inecobank::inecobank.webhook_setup_guide.step_1_description', ['link' => '<a href="https://dashboard.stripe.com/" target="_blank">InecoBank Dashboard</a>'])) !!}</p>
    </li>

    <li>
        <p><strong>{{ trans('plugins/inecobank::inecobank.webhook_setup_guide.step_2_label') }}:</strong> {!! BaseHelper::clean(trans('plugins/inecobank::inecobank.webhook_setup_guide.step_2_description', ['url' => '<code>' . route('payments.inecobank.webhook') . '</code>'])) !!}</p>
    </li>

    <li>
        <p><strong>{{ trans('plugins/inecobank::inecobank.webhook_setup_guide.step_3_label') }}:</strong> {{ trans('plugins/inecobank::inecobank.webhook_setup_guide.step_3_description') }}</p>
    </li>

    <li>
        <p><strong>{{ trans('plugins/inecobank::inecobank.webhook_setup_guide.step_4_label') }}:</strong> {{ trans('plugins/inecobank::inecobank.webhook_setup_guide.step_4_description') }}</p>
    </li>
</ol>
*/
@endphp
