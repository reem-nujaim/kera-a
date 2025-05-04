<div class="bg-white p-6 shadow-lg rounded-lg">
    <h3 class="text-xl font-semibold mb-4">📦 {{ __('web.orders_report') }}</h3>

    <!-- تقرير عدد الطلبات حسب الحالة -->
    <h4 class="text-lg font-semibold">📌 {{ __('web.order_status') }}</h4>
    <table class="min-w-full bg-white border border-gray-300 mt-4">
        <thead class="bg-sky_blue">
            <tr>
                <th class="py-2 px-4 border text-center font-bold">{{ __('web.status') }}</th>
                <th class="py-2 px-4 border text-center font-bold">{{ __('web.order_count') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr class="bg-gray-50">
                <td class="py-2 px-4 border text-center">{{ ucfirst(__('web.Request_Status.' . $order->status)) }}</td>
                <td class="py-2 px-4 border text-center">{{ $order->count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- تقرير أكثر المنتجات طلبًا -->
    <h4 class="mt-6 text-lg font-semibold">🔥 {{ __('web.top_items') }}</h4>
    <table class="min-w-full bg-white border border-gray-300 mt-4">
        <thead class="bg-sky_blue">
            <tr>
                <th class="py-2 px-4 border text-center font-bold">{{ __('web.item_id') }}</th>
                <th class="py-2 px-4 border text-center font-bold">{{ __('web.order_count') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topItems as $item)
            <tr class="bg-gray-50">
                <td class="py-2 px-4 border text-center">{{ $item->item_id }}</td>
                <td class="py-2 px-4 border text-center">{{ $item->count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- تقرير الطلبات لكل عميل -->
    <h4 class="mt-6 text-lg font-semibold">👥 {{ __('web.orders_per_customer') }}</h4>
    <table class="min-w-full bg-white border border-gray-300 mt-4">
        <thead class="bg-sky_blue">
            <tr>
                <th class="py-2 px-4 border text-center font-bold">{{ __('web.customer') }}</th>
                <th class="py-2 px-4 border text-center font-bold">{{ __('web.order_count') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ordersPerCustomer as $customer)
            <tr class="bg-gray-50">
                <td class="py-2 px-4 border text-center">{{ $customer->user->first_name }} {{ $customer->user->last_name }}</td>
                <td class="py-2 px-4 border text-center">{{ $customer->count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
