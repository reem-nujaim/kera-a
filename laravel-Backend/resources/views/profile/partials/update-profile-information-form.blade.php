<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('web.Profile Information') }}
        </h2>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        {{-- <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div> --}}

        <div class="flex justify-center mb-6">
            <div class="relative w-24 h-24 rounded-full bg-white overflow-hidden group">
                <img id="profileImage" src="{{ $user->user_image ? asset('storage/' . $user->user_image) : asset('default_images/default_profile.jpg') }}" alt="Admin Photo" class="object-cover w-full h-full">
                <label for="uploadInput" class="absolute inset-0 bg-black bg-opacity-50 flex justify-center items-center text-white text-sm opacity-0 group-hover:opacity-100 cursor-pointer">
                    Change Image
                </label>
                <input id="uploadInput" name="user_image" type="file" accept="image/*" class="hidden" onchange="previewImage(event)">
            </div>
        </div>
        <div>
            <x-input-label for="first_name" :value="__('web.First Name')" />
            <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full" :value="old('first_name', $user->first_name)" required autofocus autocomplete="given-name" />
            <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
        </div>

        <div class="mt-4">
            <x-input-label for="last_name" :value="__('web.Last Name')" />
            <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full" :value="old('last_name', $user->last_name)" required autocomplete="family-name" />
            <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('web.Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>
        <!-- phone -->
        <div class="mt-4">
            <x-input-label for="phone" :value="__('web.Phone Number')" />
            <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-full" :value="old('phone', $user->phone)" required autocomplete="tel" maxlength="9" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('web.Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
    <!-- JavaScript -->
  <script>
    // Function to preview the uploaded profile image
    function previewImage(event) {
      const reader = new FileReader();
      reader.onload = function () {
        const output = document.getElementById('profileImage');
        output.src = reader.result; // Set the new image URL
      };
      reader.readAsDataURL(event.target.files[0]);
    }
  </script>
</section>
