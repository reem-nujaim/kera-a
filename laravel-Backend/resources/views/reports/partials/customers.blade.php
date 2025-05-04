<div class="bg-white p-6 shadow-lg rounded-lg">
    <h3 class="text-xl font-semibold mb-4">👤 {{ __('web.customers_report') }}</h3>

    <!-- تقرير العملاء الجدد شهريًا -->
    <h4 class="text-lg font-semibold">📅 {{ __('web.new_customers_monthly') }}</h4>
    <table class="min-w-full bg-white border border-gray-300 mt-4">
        <thead class="bg-sky_blue">
            <tr>
                <th class="py-2 px-4 border text-center font-bold">{{ __('web.month') }}</th>
                <th class="py-2 px-4 border text-center font-bold">{{ __('web.new_customers_count') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($newCustomers as $month)
            <tr class="bg-gray-50">
                <td class="py-2 px-4 border text-center">{{ $month->month }}</td>
                <td class="py-2 px-4 border text-center">{{ $month->count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- تقرير العملاء الأكثر إنفاقًا -->
    <h4 class="mt-6 text-lg font-semibold">💸 {{ __('web.top_spenders') }}</h4>
    <table class="min-w-full bg-white border border-gray-300 mt-4">
        <thead class="bg-sky_blue">
            <tr>
                <th class="py-2 px-4 border text-center font-bold">{{ __('web.customer') }}</th>
                <th class="py-2 px-4 border text-center font-bold">{{ __('web.total_spent') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topSpenders as $spender)
            <tr class="bg-gray-50">
                <td class="py-2 px-4 border text-center">{{ $spender->rentalRequest->user->first_name }} {{ $spender->rentalRequest->user->last_name }}</td>
                <td class="py-2 px-4 border text-center">{{ number_format($spender->total_spent, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
