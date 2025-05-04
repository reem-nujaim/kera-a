@extends('layout.master')

@section('body')
    <div class="container mx-auto bg-white p-6 shadow-md rounded-lg">
        <h1 class="text-2xl font-bold text-secondary mb-4">{{ __('web.Users List') }}</h1>

        {{-- عرض رسالة نجاح إذا كانت موجودة --}}
        @if (session('success') && is_array(session('success')))
            <div class="flex items-center {{ session('success')['color'] }} text-white text-center py-2 rounded mb-4">
                <span class="flex-1">{{ session('success')['message'] }}</span>
                <button onclick="this.parentElement.style.display='none'" class="ml-2">
                    <span class="text-xl">&times;</span>
                </button>
            </div>
        @endif

        <table class="w-full border-collapse border border-gray-300 text-sm">
            <thead>
                <tr class="bg-sky_blue">
                    <th class="border border-gray-300 px-4 py-2">{{ __('web.ID') }}</th>
                    <th class="border border-gray-300 px-4 py-2">{{ __('web.Name') }}</th>
                    <th class="border border-gray-300 px-4 py-2">{{ __('web.Email') }}</th>
                    <th class="border border-gray-300 px-4 py-2">{{ __('web.Phone Number') }}</th>
                    {{-- <th class="border border-gray-300 px-4 py-2">{{ __('web.Role') }}</th> --}}
                    <th class="border border-gray-300 px-4 py-2">{{ __('web.Address') }}</th>
                    <th class="border border-gray-300 px-4 py-2">{{ __('web.Created At') }}</th>
                    {{-- <th class="border border-gray-300 px-4 py-2">{{ __('web.ID Card Number') }}</th> --}}
                    {{-- <th class="border border-gray-300 px-4 py-2">{{ __('web.ID Card Image') }}</th> --}}
                    <th class="border border-gray-300 px-4 py-2">{{ __('web.User Image') }}</th>
                    <th class="border border-gray-300 px-4 py-2">{{ __('web.Account Status') }}</th>
                    <th class="border border-gray-300 px-4 py-2">{{ __('web.Verification Status') }}</th>
                    <th class="border border-gray-300 px-4 py-2">{{ __('web.Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr class="hover:bg-gray-100">
                        <td class="px-4 py-2 text-center">{{ $user->id }}</td>
                        <td class="px-4 py-2 text-center">{{ $user->first_name }} {{ $user->last_name }}</td>
                        <td class="px-4 py-2 text-center">{{ $user->email }}</td>
                        <td class="px-4 py-2 text-center">{{ $user->phone }}</td>
                        {{-- <td class="px-4 py-2 text-center">{{ $user->is_admin ? 'Admin' : 'User' }}</td> --}}
                        <td class="px-4 py-2 text-center">{{ $user->address ?? 'No Address' }}</td>
                        <td class="px-4 py-2 text-center">{{ $user->created_at->format('d/m/Y H:i A') }}</td>
                        {{-- <td class="px-4 py-2 text-center">{{ $user->identity_card_number ?? '---' }}</td> --}}
                        {{-- <td class="px-4 py-2 text-center">
                           @if ($user->identity_card_image_front)
                                <img src="{{ asset('storage/' . $user->identity_card_image_front) }}" alt="ID Card Front"
                                    class="w-16 h-16 mx-auto">
                            @else
                                ---
                            @endif
                        </td> --}}
                        <td class="px-4 py-2 text-center">
                            @if ($user->user_image)
                                <img src="{{ asset('storage/' . $user->user_image) }}" alt="User Image"
                                    class="w-16 h-16 mx-auto">
                            @else
                                ---
                            @endif
                        </td>

                         <td class="px-4 py-2 text-center">
                            <!-- زر التفعيل / التعطيل -->
                            <form action="{{ route('customers.toggleStatus', $user->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="{{ $user->is_active ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600' }} text-white px-4 py-2 rounded-full text-sm">
                                    {{ $user->is_active ? __('web.Deactivate') : __('web.Activate') }}
                                </button>
                            </form>
                        </td>


                        <td class="px-4 py-2 text-center text-nowrap">
                            <a href="{{ route('customers.verify', $user->id) }}"
                                class="px-4 py-2 rounded-full text-white text-sm {{ $user->is_verified ? 'bg-green-500 hover:bg-green-600' : 'bg-red-500 hover:bg-red-600' }}">
                                {{ $user->is_verified ? __('web.Verified') : __('web.Not Verified') }}
                            </a>
                        </td>


                        <td class="px-4 py-2 text-center">
                            <!-- زر الحذف -->
                            <button onclick="showAlert({{ $user->id }})"
                                class="hover:bg-red-600 p-2 rounded-full hover:bg-gray-400 flex items-center justify-center bg-gray-200 mx-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M6,19a2.006,2.006,0,0,0,2,2h8a2.006,2.006,0,0,0,2-2V7H6ZM19,4H15.5l-1-1h-5l-1,1H5V6H19Z" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>


    <!-- Alert Modal -->
<div id="deleteAlert" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg text-center w-80">
        <h3 class="text-primary text-lg font-semibold mb-4">{{__('web.Are you sure?')}}</h3>
        <form id="deleteForm" method="POST" action="">
            @csrf
            @method('DELETE')
            <div class="flex justify-between">
                <button type="submit"
                    class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary">{{__('web.Yes')}}</button>
                <button type="button" onclick="hideAlert()" class="bg-sky_blue px-4 py-2 rounded-lg hover:bg-gray-200">{{__('web.Cancel')}}</button>
            </div>
        </form>
    </div>
</div>


<script>
    function showAlert(id) {
        const form = document.getElementById('deleteForm');
        const lang = '{{ app()->getLocale() }}'; // جلب اللغة الحالية من السيرفر
        form.action = `/${lang}/customers/${id}`; // إضافة اللغة إلى الرابط الخاص بالحذف
        document.getElementById('deleteAlert').classList.remove('hidden');
    }

    function hideAlert() {
        document.getElementById('deleteAlert').classList.add('hidden');
    }
</script>
@endsection
