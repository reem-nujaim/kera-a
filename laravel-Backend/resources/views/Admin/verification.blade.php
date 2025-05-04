@extends('layout.master')

@section('body')
<div class="container max-w-4xl mx-auto bg-white p-6 shadow-md rounded-lg">
    <!-- عرض رسائل النجاح أو الخطأ -->
    @if (session('success') || session('error'))
        @php
            $message = session('success') ?? session('error');
            $message = is_array($message) ? implode(', ', $message) : $message;
        @endphp
        <div class="relative bg-{{ session('success') && $user->is_verified ? 'green-500' : 'red-500' }} text-white p-4 rounded-lg shadow-lg flex items-center justify-between mb-6 text-base">
            <span class="flex-1">
                {{ __('web.' . $message) }}
            </span>
            <button onclick="this.parentElement.remove();" class="ml-4 text-xl font-bold hover:text-gray-300">
                &times;
            </button>
        </div>
    @endif

    <!-- العنوان والزر العلوي -->
    <div class="bg-secondary text-white p-4 rounded-lg mb-6">
        <div class="flex justify-between items-center">
            <h1 class="text-xl font-semibold">{{ __('web.Verification Page') }}</h1>
            <a href="{{ route('customers.index') }}" class="bg-white text-secondary text-base font-medium px-5 py-2 rounded hover:bg-beige shadow">
                {{ __('web.Back') }}
            </a>
        </div>
    </div>

    <!-- جدول المعلومات -->
    <table class="w-full ltr:text-left rtl:text-right max-w-2xl mx-auto border-collapse border border-gray-300 text-lg mt-6">
        <tr><th class="border px-4 py-3 bg-gray-100">{{ __('web.ID') }}</th><td class="border px-4 py-3">{{ $user->id }}</td></tr>
        <tr><th class="border px-4 py-3 bg-gray-100">{{ __('web.Name') }}</th><td class="border px-4 py-3">{{ $user->first_name }} {{ $user->last_name }}</td></tr>
        <tr><th class="border px-4 py-3 bg-gray-100">{{ __('web.Email') }}</th><td class="border px-4 py-3">{{ $user->email }}</td></tr>
        <tr><th class="border px-4 py-3 bg-gray-100">{{ __('web.Phone Number') }}</th><td class="border px-4 py-3">{{ $user->phone }}</td></tr>
        <tr><th class="border px-4 py-3 bg-gray-100">{{ __('web.ID Card Number') }}</th><td class="border px-4 py-3">{{ $user->identity_card_number ?? '---' }}</td></tr>
        <tr>
            <th class="border px-4 py-3 bg-gray-100">{{ __('web.Front ID Card Image') }}</th>
            <td class="border px-4 py-3">
                @if ($user->identity_card_image_front)
                    <img src="{{ asset('storage/' . $user->identity_card_image_front) }}" alt="ID Front" class="w-24 h-24 rounded-lg">
                @else
                    ---
                @endif
            </td>
        </tr>
        <tr>
            <th class="border px-4 py-3 bg-gray-100">{{ __('web.Back ID Card Image') }}</th>
            <td class="border px-4 py-3">
                @if ($user->identity_card_image_back)
                    <img src="{{ asset('storage/' . $user->identity_card_image_back) }}" alt="ID Back" class="w-24 h-24 rounded-lg">
                @else
                    ---
                @endif
            </td>
        </tr>
    </table>

    <!-- زر التحقق -->
    <form action="{{ route('customers.toggleVerification', $user->id) }}" method="POST" class="mt-6 text-center">
        @csrf
        @method('PATCH')
        <button type="submit"
            class="px-5 py-2 rounded-full text-white text-lg {{ $user->is_verified ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600' }}">
            {{ $user->is_verified ? __('web.Not Verified') : __('web.Verified') }}
        </button>
    </form>
</div>
@endsection
