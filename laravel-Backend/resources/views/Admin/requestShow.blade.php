@extends('layout.master')

@section('body')
    <div class="container mx-auto bg-white p-8 shadow-xl rounded-lg space-y-8">

        <!-- عنوان الصفحة وزر العودة -->
        <div class="bg-secondary text-white p-4 mt-2 rounded-t-lg">
            <div class="container mx-auto flex justify-between items-center">
                <h1 class="text-xl font-bold">{{ __('web.Request Details') }}</h1>
                <a href="{{ route('requests.index') }}"
                    class="bg-white text-secondary font-semibold px-4 py-2 rounded hover:bg-beige">
                    {{ __('web.Back') }}
                </a>
            </div>
        </div>

        <!-- عرض رسائل النجاح أو الخطأ -->
        @if (session('success') || session('error'))
            <div
                class="relative bg-{{ session('success') ? 'green-500' : 'red-500' }} text-white p-4 rounded-lg shadow-lg flex items-center justify-between max-w-full mb-4 mx-auto max-w-3xl">
                <span class="flex-1">
                    {{ session('success') ?? session('error') }}
                </span>
                <button onclick="this.parentElement.remove();" class="ml-4 text-xl font-bold hover:text-gray-300">
                    &times;
                </button>
            </div>
        @endif

        <!-- تفاصيل الطلب -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
                <h2 class="font-semibold text-xl text-gray-800">{{ __('web.Request ID') }}:</h2>
                <p class="text-lg text-gray-600">{{ $request->id }}</p>
            </div>
            <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
                <h2 class="font-semibold text-xl text-gray-800">{{ __('web.Customer Name') }}:</h2>
                <p class="text-lg text-gray-600">{{ optional($request->user)->first_name }}
                    {{ optional($request->user)->last_name }}</p>
            </div>
            <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
                <h2 class="font-semibold text-xl text-gray-800">{{ __('web.Product Name') }}:</h2>
                <p class="text-lg text-gray-600">{{ optional($request->item)->name ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- تفاصيل إضافية -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
            <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
                <h2 class="font-semibold text-xl text-gray-800">{{ __('web.Start Date') }}:</h2>
                <p class="text-lg text-gray-600">{{ optional($request->start_date)->format('H:i d/m/Y') }}</p>
            </div>
            <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
                <h2 class="font-semibold text-xl text-gray-800">{{ __('web.End Date') }}:</h2>
                <p class="text-lg text-gray-600">{{ optional($request->end_date)->format('H:i d/m/Y') ?? 'N/A' }}</p>
            </div>
            <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
                <h2 class="font-semibold text-xl text-gray-800">{{ __('web.Request Date') }}:</h2>
                <p class="text-lg text-gray-600">{{ optional($request->request_date)->format('H:i d/m/Y') }}</p>
            </div>
            <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
                <h2 class="font-semibold text-xl text-gray-800">{{ __('web.Request Status') }}:</h2>
                <p class="text-lg text-gray-600">{{ __('web.Request_Status.' . $request->status) }}</p>
            </div>
            <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
                <h2 class="font-semibold text-xl text-gray-800">{{ __('web.Amount') }}:</h2>
                <p class="text-lg text-gray-600">{{ $request->amount }}</p>
            </div>
            <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
                <h2 class="font-semibold text-xl text-gray-800">{{ __('web.Payment Method') }}:</h2>
                <p class="text-lg text-gray-600">{{__('web.payment_method.' . $request->payment_method) }}</p>
            </div>
            <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
                <h2 class="font-semibold text-xl text-gray-800">{{ __('web.Delivery Method') }}:</h2>
                <p class="text-lg text-gray-600">{{__('web.delivery_method.' . $request->delivery_method) }}</p>
            </div>
            <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
                <h2 class="font-semibold text-xl text-gray-800">{{ __('web.Address') }}:</h2>
                <p class="text-lg text-gray-600">{{$request->user->address }}</p>
            </div>
            <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
                <h2 class="font-semibold text-xl text-gray-800">{{ __('web.Transaction Number') }}:</h2>
                <p class="text-lg text-gray-600">
                    @if($request->transaction_number)
                        {{ $request->transaction_number }}
                    @else
                        {{ __('web.Not Found') }}
                    @endif
                </p>
            </div>

            <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
                <h2 class="font-semibold text-xl text-gray-800">{{ __('web.Payment Status') }}:</h2>
                <p class="text-lg text-gray-600">{{  __('web.Requset_Payment_status.' . ucfirst($request->payment_status)) }}</p>
            </div>
            <a href="{{route('bills.index')}}" class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 text-center">
                <h2 class="font-semibold text-xl text-gray-800">{{ __('web.Show Bill') }}</h2>
            </a>
        </div>



<!-- قسم الحذف -->

<div class="mt-6 flex justify-center">
    <button onclick="showAlert({{ $request->id }})"
        class="bg-red-600 text-white font-semibold text-lg px-6 py-3 rounded-full hover:bg-red-700 transition duration-200">
        {{ __('web.Delete Request') }}
    </button>
</div>





    <!-- Alert Modal -->
    <div id="deleteAlert" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg text-center w-80">
            <h3 class="text-primary text-lg font-semibold mb-4">
                {{ __('web.Are you sure you want to delete this request?') }}</h3>
            <form id="deleteForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="flex justify-between">
                    <button type="submit"
                        class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary">{{ __('web.Yes') }}</button>
                    <button type="button" onclick="hideAlert()"
                        class="bg-sky_blue px-4 py-2 rounded-lg hover:bg-gray-200">{{ __('web.Cancel') }}</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function showAlert(id) {
    document.getElementById('deleteAlert').classList.remove('hidden');
    var formAction = "{{ route('requests.destroy', ':id') }}";
    formAction = formAction.replace(':id', id);
    document.getElementById('deleteForm').action = formAction;
}

function hideAlert() {
    document.getElementById('deleteAlert').classList.add('hidden');
}

    </script>
@endsection