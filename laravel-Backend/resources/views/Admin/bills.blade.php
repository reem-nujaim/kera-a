@extends('layout.master')

@section('body')
    <div class="container w-auto mx-auto bg-white p-6 shadow-md rounded-lg">

        {{-- رسائل النجاح والفشل --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ session('success') }}
                <span class="absolute top-0 bottom-0 close-btn px-4 py-3" onclick="this.parentElement.style.display='none';">
                    <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20">
                        <path
                            d="M14.348 14.849a1 1 0 01-1.414 0L10 11.414l-2.934 2.935a1 1 0 01-1.414-1.415L8.586 10 5.651 7.065a1 1 0 011.415-1.414L10 8.586l2.934-2.935a1 1 0 111.414 1.415L11.414 10l2.935 2.934a1 1 0 010 1.415z" />
                    </svg>
                </span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ session('error') }}
                <span class="absolute top-0 bottom-0 close-btn px-4 py-3"
                    onclick="this.parentElement.style.display='none';">
                    <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20">
                        <path
                            d="M14.348 14.849a1 1 0 01-1.414 0L10 11.414l-2.934 2.935a1 1 0 01-1.414-1.415L8.586 10 5.651 7.065a1 1 0 011.415-1.414L10 8.586l2.934-2.935a1 1 0 111.414 1.415L11.414 10l2.935 2.934a1 1 0 010 1.415z" />
                    </svg>
                </span>
            </div>
        @endif

        {{-- تحديد مكان زر الإغلاق بناءً على اللغة --}}
        <style>
            [lang="ar"] .close-btn {
                left: 0;
                /* حرك زر الإغلاق إلى الجهة اليسرى عند اللغة العربية */
            }

            [lang="en"] .close-btn {
                right: 0;
                /* حرك زر الإغلاق إلى الجهة اليمنى عند اللغة الإنجليزية */
            }
        </style>

        {{-- رسائل النجاح والفشل --}}

        <h1 class="text-2xl font-bold text-secondary mb-4">{{__('web.Bills')}}</h1>
        <table
            class="w-full p-3 text-center border-separate border-spacing-y-3 rounded-lg border-2 border-gray-300 text-xs sm:text-sm">
            <thead>
                <tr class="bg-sky_blue">
                    <th class="px-4 py-2">{{__('web.ID')}}</th>
                    <th class="px-4 py-2">{{ __('web.Request ID') }}</th>
                    <th class="px-4 py-2">{{ __('web.User Name') }}</th>
                    <th class="px-4 py-2">{{__('web.Payment Method')}}</th>
                    <th class="px-4 py-2">{{__('web.Start Date')}}</th>
                    <th class="px-4 py-2">{{__('web.End Date')}}</th>
                    <th class="px-4 py-2">{{__('web.Amount')}}</th>
                    <th class="px-4 py-2">{{__('web.Assurance Amount')}}</th>
                    <th class="px-4 py-2">{{__('web.Assurance Descrption')}}</th>
                    <th class="px-4 py-2">{{__('web.Payment Status')}}</th>
                    <th class="px-4 py-2">{{ __('web.Print') }}</th>
                    {{-- <th class="px-4 py-2">{{__('web.Procedures')}}</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach ($bills as $bill)
                    <tr class="hover:bg-beige" data-bill-id="{{ $bill->id }}">
                        <td class="px-4 py-2">{{ $bill->id }}</td>
                        <td class="px-4 py-2">{{ $bill->rental_request_id }}</td>
                        <td class="px-4 py-2">
                            {{ $bill->rental_request->user->first_name.' '.$bill->rental_request->user->last_name  ?? __('web.Unknown User') }}
                        </td>
                        <td class="px-4 py-2">{{ __('web.payment_method.' .$bill->payment_method) }}</td>
                        <td class="px-4 py-2">{{ $bill->start_date }}</td>
                        <td class="px-4 py-2">{{ $bill->end_date }}</td>
                        <td class="px-4 py-2">{{ $bill->amount }}</td>
                        <td class="px-4 py-2">{{ $bill->rental_request->assurance->amount ?? 0 }}</td>
                        <td class="px-4 py-2">
                            @if (app()->getLocale() === 'ar')
                                {{ $bill->rental_request->assurance->ar_description ?? '_' }}
                            @else
                                {{ $bill->rental_request->assurance->en_description ?? '_' }}
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            @if ($bill->payment_status === 'paid')
                                <!-- زر غير قابل للتعديل إذا كانت الفاتورة مدفوعة -->
                                <button class="text-3xl text-green-600 cursor-not-allowed">
                                    <i class="bx bxs-check-circle"></i>
                                </button>
                            @else
                                <!-- زر يسمح بتغيير الحالة إذا كانت غير مدفوعة -->
                                <button onclick="showPaymentAlert({{ $bill->id }}, {{ $bill->rental_request_id }})"
                                    class=" text-red-600 text-3xl hover:opacity-75">
                                    <i class="bx bxs-x-circle"></i>
                                </button>
                            @endif
                        </td>
                        {{-- <td class="px-4 py-2 flex justify-center items-center gap-2">
                            <button onclick="showAlert({{ $bill->id }})" class="p-2 rounded-full hover:bg-red-600"
                                title="Delete">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="18" viewBox="0 0 14 18">
                                    <path id="ic_delete_24px"
                                        d="M6,19a2.006,2.006,0,0,0,2,2h8a2.006,2.006,0,0,0,2-2V7H6ZM19,4H15.5l-1-1h-5l-1,1H5V6H19Z"
                                        transform="translate(-5 -3)" />
                                </svg>
                            </button>
                        </td> --}}
                        <td class="px-4 py-2">
                            @if ($bill->payment_status === 'paid')
                            <button onclick="printBill({{ $bill->id }})"
                                class="text-blue-600 text-2xl hover:opacity-75">
                                <i class="bx bx-printer"></i>
                            </button>
                            @else
                            <button onclick="printBill({{ $bill->id }})"
                                class="text-blue-600 text-2xl hover:opacity-75 cursor-not-allowed">
                                <i class="bx bx-printer"></i>
                            </button>
                            @endif
                        </td>

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
    <div id="deleteAlert" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg text-center w-80">
            <h3 class="text-primary text-lg font-semibold mb-4">{{ __('web.Are you sure about the deletion process?') }}
            </h3>
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
        function showPaymentAlert(billId, requestId) {
            const form = document.getElementById('paymentForm');
            const lang = '{{ app()->getLocale() }}'; // جلب اللغة الحالية من السيرفر
            form.action = `/${lang}/bills/${billId}/update-status`; // إضافة اللغة إلى الرابط الخاص بتغيير الحالة
            document.getElementById('requestID').innerText = requestId; // عرض رقم الطلب
            document.getElementById('paymentAlert').classList.remove('hidden');
        }

        function hidePaymentAlert() {
            document.getElementById('paymentAlert').classList.add('hidden');
        }

        function showAlert(id) {
            const form = document.getElementById('deleteForm');
            const lang = '{{ app()->getLocale() }}'; // جلب اللغة الحالية من السيرفر
            form.action = `/${lang}/bills/${id}`; // إضافة اللغة إلى الرابط الخاص بالحذف
            document.getElementById('deleteAlert').classList.remove('hidden');
        }

        function hideAlert() {
            document.getElementById('deleteAlert').classList.add('hidden');
        }

        //طباعة الفاتورة

        function printBill(billId) {
    let lang = document.documentElement.lang || 'ar'; // تحديد اللغة الافتراضية إذا لم تكن محددة
    let billRow = document.querySelector(`tr[data-bill-id='${billId}']`);

    if (!billRow) {
        console.error("Bill row not found for ID:", billId);
        return;
    }

    let translations = {
        en: {
            title: "Invoice Details",
            invoiceNumber: "Invoice Number",
            orderNumber: "Order Number",
            userName: "User Name",
            paymentMethod: "Payment Method",
            startDate: "Start Date",
            endDate: "End Date",
            amount: "Amount",
            assurance: "Assurance Amount",
            assuranceDesc: "Assurance Description",
            paymentStatus: "Payment Status"
        },
        ar: {
            title: "تفاصيل الفاتورة",
            invoiceNumber: "رقم الفاتورة",
            orderNumber: "رقم الطلب",
            userName: "اسم المستخدم",
            paymentMethod: "طريقة الدفع",
            startDate: "تاريخ البدء",
            endDate: "تاريخ الانتهاء",
            amount: "المبلغ",
            assurance: "مبلغ التأمين",
            assuranceDesc: "وصف التأمين",
            paymentStatus: "حالة الدفع"
        }
    };

    let localeTexts = translations[lang] || translations['ar']; // استخدم اللغة الافتراضية إذا لم يتم العثور عليها

    let billData = [...billRow.children].map((td, index) => {
        let keys = Object.keys(localeTexts);
        let key = keys[index + 1]; // تخطي العنوان
        return `<div class="detail-item">
                    <strong>${localeTexts[key] || "—"}:</strong> ${td.innerHTML}
                </div>`;
    }).join("\n");

    let printableContent = `
        <html lang="${lang}">
        <head>
            <title>${localeTexts.title} #${billId}</title>
            <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; text-align: center; direction: ${lang === 'ar' ? 'rtl' : 'ltr'}; }
                .bill-container { border: 1px solid #ccc; padding: 20px; border-radius: 8px; width: 60%; margin: auto; text-align: ${lang === 'ar' ? 'right' : 'left'}; background-color: #f9f9f9; }
                h2 { color: #333; margin-bottom: 20px; }
                .detail-item { margin-bottom: 10px; font-size: 18px; }
                strong { color: #444; display: inline-block; min-width: 150px; }
            </style>
        </head>
        <body>
            <div class="bill-container">
                <h2>${localeTexts.title}</h2>
                ${billData}
            </div>
            <script>
                window.onload = function() {
                    window.print();
                    setTimeout(() => { window.close(); }, 100);
                };
            <\/script>
        </body>
        </html>
    `;

    let printWindow = window.open('', '_blank');
    if (!printWindow) {
        alert(lang === 'ar' ? "يرجى السماح للنوافذ المنبثقة لطباعة الفاتورة." : "Please allow pop-ups to print the invoice.");
        return;
    }

    printWindow.document.open();
    printWindow.document.write(printableContent);
    printWindow.document.close();
}



    </script>
@endsection
