<div class="bg-white p-6 shadow-lg rounded-lg">
    <h3 class="text-xl font-semibold mb-4">📊 {{ __('web.payments_report') }}</h3>

    <table id="reportTable" class="min-w-full bg-white border border-gray-300">
        <thead class="bg-sky_blue">
            <tr>
                <th class="py-2 px-4 border text-center font-bold">#</th>
                <th class="py-2 px-4 border text-center font-bold">{{ __('web.Payment Method') }}</th>
                <th class="py-2 px-4 border text-center font-bold">{{ __('web.transaction_count') }}</th>
                <th class="py-2 px-4 border text-center font-bold">{{ __('web.total_payments') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $index => $payment)
            <tr class="bg-gray-50">
                <td class="py-2 px-4 border text-center">{{ $index + 1 }}</td>
                <td class="py-2 px-4 border text-center">
                    {{ $payment->payment_method == 'cash' ? __('web.cash_payment') : __('web.bank_transfer') }}
                </td>
                <td class="py-2 px-4 border text-center">{{ $payment->count }}</td>
                <td class="py-2 px-4 border text-center">{{ number_format($payment->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h4 class="mt-6 text-lg font-semibold">📈 {{ __('web.monthly_payment_stats') }}</h4>

    <table id="monthlyStatsTable" class="min-w-full bg-white border border-gray-300 mt-4">
        <thead class="bg-sky_blue">
            <tr>
                <th class="py-2 px-4 border text-center font-bold">{{ __('web.month') }}</th>
                <th class="py-2 px-4 border text-center font-bold">{{ __('web.total_payments') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($monthlyPayments as $month)
            <tr class="bg-gray-50">
                <td class="py-2 px-4 border text-center">{{ __('web.months.'.$month->month) }}</td>
                <td class="py-2 px-4 border text-center">{{ number_format($month->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
