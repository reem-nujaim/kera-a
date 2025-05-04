@extends('layout.master')

@section('body')
    <div class="container w-full mx-auto bg-white p-6 shadow-md rounded-lg">
        {{-- رسائل النجاح والفشل --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ session('success') }}
                <span class="absolute top-0 bottom-0 close-btn px-4 py-3" onclick="this.parentElement.style.display='none';">
                    <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M14.348 14.849a1 1 0 01-1.414 0L10 11.414l-2.934 2.935a1 1 0 01-1.414-1.415L8.586 10 5.651 7.065a1 1 0 011.415-1.414L10 8.586l2.934-2.935a1 1 0 111.414 1.415L11.414 10l2.935 2.934a1 1 0 010 1.415z" />
                    </svg>
                </span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ session('error') }}
                <span class="absolute top-0 bottom-0 close-btn px-4 py-3" onclick="this.parentElement.style.display='none';">
                    <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M14.348 14.849a1 1 0 01-1.414 0L10 11.414l-2.934 2.935a1 1 0 01-1.414-1.415L8.586 10 5.651 7.065a1 1 0 011.415-1.414L10 8.586l2.934-2.935a1 1 0 111.414 1.415L11.414 10l2.935 2.934a1 1 0 010 1.415z" />
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

        <div class="text-white mt-2 p-4">
            <div class="container mx-auto flex justify-between items-center">
                <h1 class="text-2xl font-bold text-secondary">{{__('web.Assurances')}}</h1>
                <a href="{{ route('assurances.create') }}" class=" font-semibold text-secondary px-4 py-2 rounded hover:bg-beige">
                    +{{__('web.Add Assurance')}}</a>
            </div>
        </div>

        <table class="w-full p-3 text-center border-separate border-spacing-y-3 rounded-lg border-2 border-gray-300 text-xs sm:text-sm">
            <thead>
                <tr class="bg-sky_blue">
                    <th class="px-4 py-2">{{__('web.ID')}}</th>
                    <th class="px-4 py-2">{{__('web.Request ID')}}</th>
                    <th class="px-4 py-2">{{__('web.Product Name')}}</th>
                    <th class="px-4 py-2">{{__('web.Customer Name')}}</th>
                    <th class="px-4 py-2">{{__('web.Assurance Amount')}}</th>
                    <th class="px-4 py-2">{{__('web.Descrieption')}}</th>
                    <th class="px-4 py-2">{{__('web.Date')}}</th>
                    <th class="px-4 py-2">{{__('web.Is Returned')}}</th>
                    <th class="px-4 py-2">{{__('web.Procedures')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($assurances as $assurance)
                    <tr class="hover:bg-beige">
                        <td class="px-4 py-2">{{ $assurance->id }}</td>
                        <td class="px-4 py-2">{{ $assurance->rental_request->id ?? 'N/A' }}</td>
                        <td class="px-4 py-2">{{ $assurance->rental_request->item->name ?? 'N/A' }}</td>
                        <td class="px-4 py-2">{{ $assurance->rental_request->user->first_name . ' ' .$assurance->rental_request->user->last_name ?? 'N/A' }}</td>
                        <td class="px-4 py-2">{{ $assurance->amount }}</td>
                        <td class="px-4 py-2">
                            @if (app()->getLocale() === 'ar')
                                {{ $assurance->ar_description }}
                            @else
                                {{ $assurance->en_description }}
                            @endif
                        </td>
                        <td class="px-4 py-2">{{ $assurance->created_at->format('d/m/Y h:i A') }}</td>
                        {{-- <td class="px-4 py-2">
                            @if ($assurance->is_returned)
                                <button class="bg-green-700 text-white px-4 py-1 rounded-full text-sm">Returned</button>
                            @else
                                <button class="bg-red-500 text-white px-4 py-1 rounded-full text-sm">Not Returned</button>
                            @endif
                        </td> --}}
                        <td class="px-4 py-2">
                            @if ($assurance->is_returned)
                                <!-- زر غير قابل للتعديل إذا تم الارجاع -->
                                <button class="text-3xl text-green-600 cursor-not-allowed">
                                    <i class="bx bxs-check-circle"></i>
                                </button>
                            @else
                                <!-- زر يسمح بتغيير الحالة إذا لم يتم الارجاع -->
                                <button onclick="showReturnAlert({{ $assurance->id }}, {{ $assurance->rental_request->id }})"
                                    class=" text-red-600 text-3xl hover:opacity-75">
                                    <i class="bx bxs-x-circle"></i>
                                </button>
                            @endif
                        </td>
                        <td class="px-4 py-2 flex justify-center gap-2">
                            <button onclick="showAlert({{ $assurance->id }})" class="hover:fill-red-700 p-2 rounded-full hover:bg-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="18" viewBox="0 0 14 18">
                                    <path id="ic_delete_24px" d="M6,19a2.006,2.006,0,0,0,2,2h8a2.006,2.006,0,0,0,2-2V7H6ZM19,4H15.5l-1-1h-5l-1,1H5V6H19Z" transform="translate(-5 -3)" />
                                </svg>
                            </button>
                            @if ($assurance->is_returned)
                            <a href="{{ route('assurances.edit', $assurance->id) }}" class="hidden hover:fill-blue-700  p-2 rounded-full hover:bg-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18.003" height="18.003" viewBox="0 0 18.003 18.003">
                                    <path id="ic_create_24px" d="M3,17.25V21H6.75L17.81,9.94,14.06,6.19ZM20.71,7.04a1,1,0,0,0,0-1.41L18.37,3.29a1,1,0,0,0-1.41,0L15.13,5.12l3.75,3.75,1.83-1.83Z" transform="translate(-3 -2.997)"/>
                                </svg>
                            </a>
                            @else
                            <a href="{{ route('assurances.edit', $assurance->id) }}" class="hover:fill-blue-700 p-2 rounded-full hover:bg-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18.003" height="18.003" viewBox="0 0 18.003 18.003">
                                    <path id="ic_create_24px" d="M3,17.25V21H6.75L17.81,9.94,14.06,6.19ZM20.71,7.04a1,1,0,0,0,0-1.41L18.37,3.29a1,1,0,0,0-1.41,0L15.13,5.12l3.75,3.75,1.83-1.83Z" transform="translate(-3 -2.997)"/>
                                </svg>
                            </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

        <!-- Alert Modal Return-->
        <div id="ReturnAlert" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden">
            <div class="bg-white p-6 rounded-lg shadow-lg text-center w-80">
                <h3 class="text-primary text-lg font-semibold mb-4">
                    {{ __('web.Are you sure you want to change the return status?') }}</h3>
                <p class="text-sm text-gray-600 mb-4">
                    {{ __('web.Assurance ID') }}: <span id="assuranceID"></span>
                </p>
                <form id="ReturnForm" method="POST" action="">
                    @csrf
                    <div class="flex justify-between">
                        <button type="submit"
                            class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary">{{ __('web.Yes') }}</button>
                        <button type="button" onclick="hideReturnAlert()"
                            class="bg-sky_blue px-4 py-2 rounded-lg hover:bg-gray-200">{{ __('web.Cancel') }}</button>
                    </div>
                </form>
            </div>
        </div>


    <!-- Modal الحذف -->
    <form id="deleteForm" method="POST" action="" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <div id="deleteAlert" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg text-center w-80">
            <h3 class="text-primary text-lg font-semibold mb-4">{{__('web.Are you sure about the deletion process?')}}</h3>
            <div class="flex justify-between">
                <button onclick="confirmDelete()" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary">{{__('web.Yes')}}</button>
                <button onclick="hideAlert()" class="bg-sky_blue px-4 py-2 rounded-lg hover:bg-gray-200">{{__('web.Cancel')}}</button>
            </div>
        </div>
    </div>

    <script>
        function showReturnAlert(assuranceId, requestId) {
    const form = document.getElementById('ReturnForm');
    const lang = '{{ app()->getLocale() }}'; // جلب اللغة الحالية من السيرفر
    form.action = `/${lang}/assurances/${assuranceId}/update-status`;
    document.getElementById('assuranceID').innerText = assuranceId; // عرض رقم الطلب
    document.getElementById('ReturnAlert').classList.remove('hidden');
        }

        function hideReturnAlert() {
        document.getElementById('ReturnAlert').classList.add('hidden');
        }
        // عرض نافذة التأكيد
        function showAlert(id) {
            document.getElementById('deleteAlert').classList.remove('hidden');
            var formAction = "{{ route('assurances.destroy', ':id') }}";
            formAction = formAction.replace(':id', id);
            document.getElementById('deleteForm').action = formAction;
        }

        // إخفاء نافذة التأكيد
        function hideAlert() {
            document.getElementById('deleteAlert').classList.add('hidden');
        }

        // تأكيد الحذف
        function confirmDelete() {
            document.getElementById('deleteForm').submit();
            hideAlert();
        }
    </script>
@endsection
