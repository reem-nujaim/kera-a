@extends('layout.master')

@section('body')
    <div class="container w-auto mx-auto bg-white p-6 shadow-md rounded-lg">
        <h1 class="text-2xl font-bold text-secondary mb-4">{{__('web.Late Fees')}}</h1>
        <table
            class="w-full p-3 text-center border-separate border-spacing-y-3 rounded-lg border-2 border-gray-300 text-xs sm:text-sm">
            <thead>
                <tr class="bg-sky_blue">
                    <th class="px-4 py-2">{{__('web.ID')}}</th>
                    <th class="px-4 py-2">{{__('web.Request ID')}}</th>
                    <th class="px-4 py-2">{{__('web.Customer Name')}}</th>
                    <th class="px-4 py-2">{{__('web.Number of late hours')}}</th>
                    <th class="px-4 py-2">{{__('web.Fee per Hour')}}</th>
                    <th class="px-4 py-2">{{__('web.Total Fee')}}</th>
                    <th class="px-4 py-2">{{__('web.Late Fee Date')}}</th>
                    <th class="px-4 py-2">{{__('web.Paid')}}</th>
                    {{-- <th class="px-4 py-2">Procedures</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach($lateFees as $lateFee)
                    <tr class="hover:bg-beige">
                        <td class="px-4 py-2">{{ $lateFee->id }}</td>
                        <td class="px-4 py-2">{{ $lateFee->rentalRequest->id ?? 'N/A' }}</td>
                        <td class="px-4 py-2">{{ $lateFee->rentalRequest->user->first_name .' '.$lateFee->rentalRequest->user->last_name  ?? 'N/A' }}</td>
                        <td class="px-4 py-2">{{ $lateFee->number_of_late_hours }}</td>
                        <td class="px-4 py-2">{{ $lateFee->fee_per_late_hour }}</td>
                        <td class="px-4 py-2">{{ $lateFee->total_fee }}</td>
                        <td class="px-4 py-2">{{ $lateFee->late_fee_date }}</td>
                        <td class="px-4 py-2">
                            @if($lateFee->paid == false)
                            <button onclick="showPaymentAlert({{ $lateFee->id }}, {{ $lateFee->rentalRequest->id }})"
                                class=" text-red-600 text-3xl hover:opacity-75">
                                <i class="bx bxs-x-circle"></i>
                            </button>
                            @else
                            <button class="text-3xl text-green-600 cursor-not-allowed">
                                <i class="bx bxs-check-circle"></i>
                            </button>
                            @endif
                        </td>
                        {{-- <td class="px-4 py-2 flex justify-center items-center gap-2">
                            <button onclick="showAlert()" class="p-2 rounded-full hover:bg-red-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="18" viewBox="0 0 14 18">
                                    <path id="ic_delete_24px" d="M6,19a2.006,2.006,0,0,0,2,2h8a2.006,2.006,0,0,0,2-2V7H6ZM19,4H15.5l-1-1h-5l-1,1H5V6H19Z" transform="translate(-5 -3)" />
                                </svg>
                            </button>
                        </td> --}}
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
        <!-- Alert Modal لتأكيد تغيير حالة الدفع -->
        <div id="paymentAlert" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden">
            <div class="bg-white p-6 rounded-lg shadow-lg text-center w-80">
                <h3 class="text-primary text-lg font-semibold mb-4">
                    {{ __('web.Are you sure you want to change the payment status?') }}</h3>
                <p class="text-sm text-gray-600 mb-4">
                    {{ __('web.Request ID') }}: <span id="requestID"></span>
                </p>
                <form id="paymentForm" method="POST" action="">
                    @csrf
                    <div class="flex justify-between">
                        <button type="submit"
                            class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary">{{ __('web.Yes') }}</button>
                        <button type="button" onclick="hidePaymentAlert()"
                            class="bg-sky_blue px-4 py-2 rounded-lg hover:bg-gray-200">{{ __('web.Cancel') }}</button>
                    </div>
                </form>
            </div>
        </div>
    <!-- Alert Modal -->
    {{-- <div id="deleteAlert" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg text-center w-80">
            <h3 class="text-primary text-lg font-semibold mb-4">Are you sure about the deletion process?</h3>
            <div class="flex justify-between">
                <button onclick="confirmDelete()"
                    class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary">Yes</button>
                <button onclick="hideAlert()" class="bg-sky_blue px-4 py-2 rounded-lg hover:bg-gray-200">Cancel</button>
            </div>
        </div>
    </div> --}}
    <script>

        function showPaymentAlert(lateFeeId, requestId) {
            const form = document.getElementById('paymentForm');
            const lang = '{{ app()->getLocale() }}'; // جلب اللغة الحالية من السيرفر
            form.action = `/${lang}/lateFees/${lateFeeId}/update-status`; // إضافة اللغة إلى الرابط الخاص بتغيير الحالة
            document.getElementById('requestID').innerText = requestId; // عرض رقم الطلب
            document.getElementById('paymentAlert').classList.remove('hidden');

        }

        function hidePaymentAlert() {
            document.getElementById('paymentAlert').classList.add('hidden');
        }
        // // Show the alert
        // function showAlert() {
        //     document.getElementById('deleteAlert').classList.remove('hidden');
        // }

        // // Hide the alert
        // function hideAlert() {
        //     document.getElementById('deleteAlert').classList.add('hidden');
        // }

        // // Confirm delete action
        // function confirmDelete() {
        //     alert("Deleted successfully");
        //     hideAlert();
        // }
    </script>
@endsection
