@extends('layout.master')

@section('body')
    <div class="container w-auto mx-auto bg-white p-6 shadow-md rounded-lg">
        <h1 class="text-2xl font-bold text-secondary mb-4">{{ __('web.Requests') }}</h1>


        <!-- عرض رسائل النجاح أو الخطأ -->
        @if (session('success') || session('error'))
            <div
                class="relative bg-{{ session('success') ? 'green-500' : 'red-500' }} text-white p-4 rounded-lg shadow-lg flex items-center justify-between max-w-full mb-4">
                <span class="flex-1">
                    {{ session('success') ?? session('error') }}
                </span>
                <button onclick="this.parentElement.remove();" class="ml-4 text-xl font-bold hover:text-gray-300">
                    &times;
                </button>
            </div>
        @endif

        <table
            class="w-full p-3 text-center border-separate border-spacing-y-3 rounded-lg border-2 border-gray-300 text-xs sm:text-sm">
            <thead>
                <tr class="bg-sky_blue">
                    <th class="px-4 py-2">{{ __('web.Request ID') }}</th>
                    <th class="px-4 py-2">{{ __('web.Customer Name') }}</th>
                    <th class="px-4 py-2">{{ __('web.Product Name') }}</th>
                    <th class="px-4 py-2">{{ __('web.Start Date') }}</th>
                    <th class="px-4 py-2">{{ __('web.End Date') }}</th>
                    <th class="px-4 py-2">{{ __('web.Request Date') }}</th>
                    <th class="px-4 py-2">{{ __('web.Request Status') }}</th>
                    <th class="px-4 py-2">{{ __('web.Amount') }}</th>
                    <th class="px-4 py-2">{{ __('web.Payment Method') }}</th>
                    <th class="px-4 py-2">{{ __('web.Delivery Method') }}</th>
                    <th class="px-4 py-2">{{ __('web.Address') }}</th>
                    <th class="px-4 py-2">{{ __('web.Transaction Number') }}</th>
                    <th class="px-4 py-2">{{ __('web.Payment Status') }}</th>
                    <th class="px-4 py-2">{{ __('web.Procedures') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rentalRequests as $request)
                    <tr class="hover:bg-beige">
                        <td class="px-4 py-2">{{ $request->id }}</td>
                        <td class="px-4 py-2">{{ optional($request->user)->first_name }}
                            {{ optional($request->user)->last_name }}</td>
                        <td class="px-4 py-2">{{ $request->item->name }}</td>
                        <td class="px-4 py-2">{{ $request->start_date->format('H:i d/m/Y') }}</td>
                        <td class="px-4 py-2">{{ $request->end_date ? $request->end_date->format('H:i d/m/Y') : 'N/A' }}
                        </td>
                        <td class="px-4 py-2">{{ $request->request_date->format('H:i d/m/Y') }}</td>
                        <td class="px-4 py-2">{{ __('web.Request_Status.' . $request->status) }}</td>
                        <td class="px-4 py-2">{{ $request->amount }}</td>
                        <td class="px-4 py-2">{{ __('web.payment_method.' . $request->payment_method) }}</td>
                        <td class="px-4 py-2">{{ __('web.delivery_method.' . $request->delivery_method) }}</td>
                        <td class="px-4 py-2">{{$request->user->address }}</td>
                        {{-- <td class="px-4 py-2">{{ $request->transaction_number ?? 'N/A' }}</td> --}}
                        <td class="px-4 py-2">
                            @if($request->transaction_number)
                                {{ $request->transaction_number }}
                            @else
                                {{ __('web.Not Found') }}
                            @endif
                        </td>

                        <td class="px-4 py-2">{{ __('web.Requset_Payment_status.' . ucfirst($request->payment_status)) }}
                        </td>
                        <td class="px-4 py-2 flex justify-center gap-2">


                            <button onclick="showAlert({{ $request->id }})"
                                class="bg-red-600 text-white font-semibold px-4 py-2 rounded-md hover:bg-red-700 transition duration-200">
                                {{ __('web.Delete') }}
                            </button>
                            <a href="{{ route('requests.requestsShow', ['id' => $request->id]) }}"
                                class="bg-primary text-white rounded px-4 py-2 hover:bg-secondary">
                                {{ __('web.Details') }}
                             </a>


                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
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
        // function showAlert(id) {
        //     const form = document.getElementById('deleteForm');
        //     const lang = '{{ app()->getLocale() }}'; // جلب اللغة الحالية من السيرفر
        //     form.action = `/${lang}/requests/${id}`; // إضافة اللغة إلى الرابط الخاص بالحذف
        //     document.getElementById('deleteAlert').classList.remove('hidden');
        // }
        // عرض نافذة التأكيد
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