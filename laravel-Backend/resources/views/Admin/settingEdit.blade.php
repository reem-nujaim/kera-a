
@extends('layout.master')

@section('body')
    <div class="bg-secondary text-white p-4 mt-2">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">{{ __('web.Application Edit') }}</h1>
            <a href="{{ route('setting.index') }}" class="bg-white text-secondary font-semibold px-4 py-2 rounded hover:bg-beige">
                {{ __('web.Back to Setting') }}
            </a>
        </div>
    </div>

    <main class="container mx-auto mt-6">
        <form action="{{ route('setting.update', $setting->id) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-4">
            @csrf
            @method('PATCH')

            <!-- Edit About Us -->
            <div class="bg-beige p-6 shadow-md rounded-lg">
                <h2 class="text-xl font-bold mb-4">{{ __('web.Edit About Us') }}</h2>
                <textarea name="en_about_us" class="w-full h-32 p-2 border rounded-md" placeholder="{{ __('web.Update about us information...') }}">{{ old('en_about_us', $setting->en_about_us) }}</textarea>
                <textarea name="ar_about_us" class="w-full h-32 p-2 border rounded-md mt-4" placeholder="{{ __('web.Update about us information (Arabic)...') }}">{{ old('ar_about_us', $setting->ar_about_us) }}</textarea>
            </div>

            <!-- Edit Financial Budget -->
            {{-- <div class="bg-beige p-6 shadow-md rounded-lg">
                <h2 class="text-xl font-bold mb-4">{{ __('web.Edit Financial Budget') }}</h2>
                <input type="number" name="app_budget" class="w-full p-2 border rounded-md" placeholder="{{ __('web.Update budget amount...') }}" value="{{ old('app_budget', $setting->app_budget) }}" />
            </div> --}}

            <!-- Edit Policies -->
            <div class="bg-beige p-6 shadow-md rounded-lg">
                <h2 class="text-xl font-bold mb-4">{{ __('web.Edit App Policies') }}</h2>
                <textarea name="en_privacy_policy" class="w-full h-32 p-2 border rounded-md" placeholder="{{ __('web.Update app policies...') }}">{{ old('en_privacy_policy', $setting->en_privacy_policy) }}</textarea>
                <textarea name="ar_privacy_policy" class="w-full h-32 p-2 border rounded-md mt-4" placeholder="{{ __('web.Update app policies (Arabic)...') }}">{{ old('ar_privacy_policy', $setting->ar_privacy_policy) }}</textarea>
            </div>

            <!-- Edit App Logo -->
            <div class="bg-beige p-6 shadow-md rounded-lg text-center">
                <h2 class="text-xl font-bold mb-4">{{ __('web.Edit App Logo') }}</h2>
                <input type="file" name="logo" class="w-full p-2 border rounded-md" />
                @if ($setting->logo)
                    <img src="{{ asset('storage/' . $setting->logo) }}" alt="{{ __('web.App Logo') }}" class="mx-auto mt-4 w-32 h-32 object-cover rounded-full">
                @endif
            </div>


          <div class="mt-4 flex justify-center gap-4 items-center">
    <button type="submit" class="px-4 py-1 bg-primary text-white rounded-md hover:bg-secondary text-md font-medium shadow-sm">
        {{ __('web.Save') }}
    </button>
    <a href="{{ route('setting.index') }}" class="px-4 py-1 bg-gray-200 text-black rounded-md hover:bg-gray-300 text-md font-medium shadow-sm">
        {{ __('web.Cancel') }}
    </a>
</div>

        </form>
    </main>
@endsection

