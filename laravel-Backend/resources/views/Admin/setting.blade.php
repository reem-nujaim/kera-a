

@extends('layout.master')

@section('body')
    <div class="bg-secondary text-white mt-2 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">{{ __('web.Application Setting') }}</h1>
            <a href="{{ route('setting.edit', $setting->id) }}" class="bg-white font-semibold text-secondary px-4 py-2 rounded hover:bg-beige">
                {{ __('web.Edit Settings') }}
            </a>
        </div>
    </div>

    <main class="container mx-auto mt-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-3">
            <!-- App Logo -->
            <div class="bg-beige p-6 shadow-lg rounded-lg text-center">
                <h2 class="text-xl font-bold mb-4">{{ __('web.App Logo') }}</h2>
                @if($setting->logo)
                    <img src="{{ asset('storage/' . $setting->logo) }}" alt="{{ __('web.App Logo') }}" class="mx-auto w-32 h-32 object-cover rounded-full">
                @else
                    <p>{{ __('web.No logo uploaded') }}</p>
                @endif
            </div>

            <!-- About Us -->
            <div class="bg-beige p-6 shadow-lg rounded-lg">
                <h2 class="text-xl font-bold mb-4">{{ __('web.About Us') }}</h2>
                <p class="text-gray-600">
                    {{ app()->getLocale() == 'ar' ? $setting->ar_about_us : $setting->en_about_us }}
                </p>
            </div>

            <!-- Financial Budget -->
            <div class="bg-beige p-6 shadow-lg rounded-lg">
                <h2 class="text-xl font-bold mb-4">{{ __('web.Financial Budget') }}</h2>
                <p class="text-gray-600">
                    {{ __('web.Current Budget') }}: <span class="font-semibold text-green-600">{{__('web.RY '). $setting->app_budget }}</span>
                </p>
            </div>

            <!-- Policies -->
            <div class="bg-beige p-6 shadow-lg rounded-lg">
                <h2 class="text-xl font-bold mb-4">{{ __('web.App Policies') }}</h2>
                <p class="text-gray-600">
                    {{ app()->getLocale() == 'ar' ? $setting->ar_privacy_policy : $setting->en_privacy_policy }}
                </p>
            </div>
        </div>
    </main>
@endsection