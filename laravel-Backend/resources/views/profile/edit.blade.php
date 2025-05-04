@extends('layout.master')
@section('body')
<div class="bg-sky_blue flex justify-center items-center min-h-screen">
    <div class="bg-beige m-3 p-2 rounded-lg shadow-lg w-full max-w-4xl">
        <div class="container mx-auto flex justify-between items-center">
        <h2 class="text-primary px-6 text-lg font-bold">{{__('web.Account Ditales')}}</h2>
        <a href="{{ route('profile.show') }}" class="bg-white text-secondary font-semibold px-4 py-2 rounded hover:bg-beige">
            {{ __('web.Back') }}
        </a>
        </div>
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <div class="max-w-md">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <div class="max-w-md">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </div>
                {{-- <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
</div>
@endsection
