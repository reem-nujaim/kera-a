@extends('layout.master')
@section('body')
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
<div class="bg-sky_blue flex justify-center items-center min-h-full">
<div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6">
    <!-- Profile Header with Image -->
    <div class="flex items-center border-b pb-3 mb-6">
      <!-- Profile Image -->
      <div class="relative w-24 h-24 rounded-full bg-gray-200 overflow-hidden group">
        <img id="profileImage"
        src="{{ $user->user_image ? asset('storage/' . $user->user_image) : asset('default_images/default_profile.jpg') }}"
        alt="Admin Photo" class="object-cover w-full h-full">
      </div>

      <!-- Admin Info -->
      <div class="mx-3 text-center">
        <h2 class="text-2xl font-semibold text-primary">{{ Auth::user()->first_name .' '. Auth::user()->last_name  }}</h2>
        <p class="text-red-600">{{__('web.Administrator')}}</p>
      </div>
    </div>

    <!-- Profile Information -->
    <div class="space-y-3">
      <div class="flex items-center justify-start">
        <span class="text-secondary px-3">{{__('web.Email')}}:</span>
        <span class="text-primary font-medium">{{Auth::user()->email}}</span>
      </div>
      <div class="flex items-center">
        <span class="text-secondary px-3">{{__('web.Phone Number')}}:</span>
        <span class="text-primary font-medium">{{Auth::user()->phone}}</span>
      </div>

      <div class="flex items-center">
        <span class="text-secondary px-3">{{__('web.Joining date')}}:</span>
        <span class="text-primary font-medium">{{Auth::user()->created_at}}</span>
      </div>

    {{-- حساب حصة الادمن --}}
    @php
        $adminCount = cache()->remember('admin_count', 60, function () {
            return \App\Models\User::Role('admin')->count();
        });
        $balance = $adminCount > 0 ? \App\Models\Setting::first()->app_budget / $adminCount : 0;
    @endphp
      <div class="flex items-center">
        <span class="text-secondary px-3">{{__('web.My balance')}}:</span>
        <span class="text-primary font-medium">{{__('web.RY ').  number_format($balance, 2) }}</span>
      </div>
    </div>

    <div class="flex justify-end mt-6">
      <a href={{route('profile.edit')}} class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary">{{__('web.Edit my profile')}}</a>
    </div>
  </div>

</div>
@endsection