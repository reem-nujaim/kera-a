@extends('layout.master')

@section('body')
<div class="container mx-auto bg-white p-8 shadow-xl rounded-lg space-y-8">
    <div class="bg-secondary text-white p-4 mt-2">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">{{ __('web.Product Details') }}</h1>
            <a href="{{ route('items.index') }}"
                class="bg-white text-secondary font-semibold px-4 py-2 rounded hover:bg-beige">
                {{ __('web.Back') }}
            </a>
        </div>
    </div>
    <!-- داخل body في الصفحة التي تريد إظهار الرسالة فيها -->
    <div id="toast-message" class="hidden fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 opacity-0 transition-opacity duration-500 ease-in-out transform-gpu p-5 text-white rounded-lg z-50 max-w-md w-full shadow-lg">
        <span id="toast-text" class="text-lg font-medium text-center block"></span>
        <button onclick="hideToast()" class="absolute top-2 right-2 text-xl font-bold text-white">
            &times;
        </button>
    </div>



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

    <!-- معلومات المنتج -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
            <h2 class="font-semibold text-xl text-gray-800">{{ __('web.ID') }}:</h2>
            <p class="text-lg text-gray-600">{{ $item->id }}</p>
        </div>
        <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
            <h2 class="font-semibold text-xl text-gray-800">{{ __('web.Customer Name') }}:</h2>
            <p class="text-lg text-gray-600">{{ optional($item->user)->first_name }}
                {{ optional($item->user)->last_name }}
            </p>
        </div>
        <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
            <h2 class="font-semibold text-xl text-gray-800">{{ __('web.Product Name') }}:</h2>
            <p class="text-lg text-gray-600">{{ $item->name }}</p>
        </div>
    </div>

    <!-- تفاصيل الوصف والصورة -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-8">
        <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
            <h2 class="font-semibold text-xl text-gray-800">{{ __('web.Product Image') }}:</h2>
            <div class="relative my-3">
                <div class="flex items-center overflow-hidden">
                    <!-- Image Display -->
                    <div class="flex space-x-4 transition-transform duration-500" id="image-carousel">
                        @if(is_array($item->images = json_decode($item->images, true)) && count($item->images) > 0)
                            @foreach($item->images as $image)
                                <img src="{{ asset('storage/') . $image }}" alt="{{ $item->name }}" class="w-40 h-40 object-cover rounded-lg">
                            @endforeach
                        @else
                            <p class="text-gray-600">{{ __('web.No images available.') }}</p>
                        @endif
                    </div>
                </div>
                <!-- Navigation Buttons -->
                <button onclick="moveCarousel(1)"
                    class="absolute rtl:left-0 ltr:right-0 top-1/2 transform -translate-y-1/2 bg-gray-500 text-white p-2 rounded-full">
                    &gt;
                </button>
                <button onclick="moveCarousel(-1)"
                    class="absolute rtl:right-0 ltr:left-0 top-1/2 transform -translate-y-1/2 bg-gray-500 text-white p-2 rounded-full">
                    &lt;
                </button>
            </div>
        </div>
        <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
            <h2 class="font-semibold text-xl text-gray-800">{{ __('web.Descrieption') }}:</h2>
            <p class="text-lg text-gray-600">{{ $item->description }}</p>
        </div>
    </div>



    <!-- تفاصيل إضافية في صف واحد -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mt-8">
        <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
            <h2 class="font-semibold text-xl text-gray-800">{{ __('web.Category') }}:</h2>
            <p class="text-lg text-gray-600">{{ html_entity_decode($item->category->name) }}</p>
        </div>
        <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
            <h2 class="font-semibold text-xl text-gray-800">{{ __('web.Product Location') }}:</h2>
            <p class="text-lg text-gray-600">{{ $item->location }}</p>
        </div>
        <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
            <h2 class="font-semibold text-xl text-gray-800">{{ __('web.Product Status') }}:</h2>
            <p class="text-lg text-gray-600">{{ $item->status }}</p>
        </div>

        <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
            <h2 class="font-semibold text-xl text-gray-800">{{ __('web.Quantity') }}:</h2>
            <p class="text-lg text-gray-600">{{ $item->quantity }}</p>
        </div>
        <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
            <h2 class="font-semibold text-xl text-gray-800">{{ __('web.Availability') }}:</h2>
            <p class="text-lg text-gray-600">{{ $item->available ? __('web.Available') : __('web.Not_available') }}</p>
        </div>
        <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
            <h2 class="font-semibold text-xl text-gray-800">{{ __('web.Assurance Amount') }}:</h2>
            <p class="text-lg text-gray-600">{{ $item->price_assurance }}</p>
        </div>

    </div>

    <!-- تفاصيل إضافية في صف واحد -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 mt-8">
        <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
            <h2 class="font-semibold text-xl text-gray-800">{{ __('web.Price per Hour') }}:</h2>
            <p class="text-lg text-gray-600">{{ html_entity_decode($item->price_per_hour) }}</p>
        </div>
        <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
            <h2 class="font-semibold text-xl text-gray-800">{{ __('web.Price per Day') }}:</h2>
            <p class="text-lg text-gray-600">{{ $item->price_per_day }}</p>
        </div>
        <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
            <h2 class="font-semibold text-xl text-gray-800">{{ __('web.Price per Week') }}:</h2>
            <p class="text-lg text-gray-600">{{ $item->price_per_day * 7 }}</p>
        </div>
        <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
            <h2 class="font-semibold text-xl text-gray-800">{{ __('web.Price per Month') }}:</h2>
            <p class="text-lg text-gray-600">{{ $item->price_per_day * 30 }}</p>
        </div>
    </div>

    <!-- تفاصيل إضافية في صف واحد -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mt-8">
        <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
            <h2 class="font-semibold text-xl text-gray-800">{{ __('web.Lease') }}:</h2>
            <p class="text-lg text-gray-600">{{ $item->min_rental_duration }} - {{ $item->max_rental_duration }}</p>
        </div>
        <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
            <h2 class="font-semibold text-xl text-gray-800">{{ __('web.Availability hours') }}:</h2>
            <p class="text-lg text-gray-600">{{ $item->availability_hours }}</p>
        </div>
        <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
            <h2 class="font-semibold text-xl text-gray-800">{{ __('web.Delivery Method') }}:</h2>
            <p class="text-lg text-gray-600">{{ __('web.delivery_method.' . $item->delivery_method) }}</p>
        </div>

    </div>



    <!-- موافقة الإدمن -->
    <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 mt-8 text-center">
        <h2 class="font-semibold text-2xl text-gray-800 mb-2">{{ __('web.Admin approval') }}:</h2>
        <p class="text-lg text-gray-600 mb-6" id="approval-status">
            {{ $item->admin_approval ? __('web.Approved') : __('web.Not Approved') }}
        </p>

        <div class="flex justify-center gap-4">
            <button id="approve-btn"
                class="text-xl px-6 py-2 text-green-600 hover:text-green-800 border border-green-600 rounded-full flex items-center">
                <i class="bx bxs-check-circle mr-2"></i> {{ __('web.Approve') }}
            </button>
            <button id="reject-btn"
                class="text-xl px-6 py-2 text-red-600 hover:text-red-800 border border-red-600 rounded-full flex items-center">
                <i class="bx bxs-x-circle mr-2"></i> {{ __('web.Reject') }}
            </button>
        </div>

 <!-- Toast Message -->
        <div id="toast-message" class="fixed bottom-10 left-1/2 transform -translate-x-1/2 bg-green-600 text-white text-xl px-8 py-4 rounded-lg shadow-lg hidden opacity-0 transition-all duration-500">
            <span id="toast-text"></span>
        </div>
        
    </div>
    
<!-- </div>
 -->
<script>
    document.getElementById('approve-btn').addEventListener('click', function () {
        const itemId = @json($item->id);

        fetch(`/items/${itemId}/approve`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                admin_approval: true
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('approval-status').textContent = '{{ __('web.Approved') }}';
                    showToast('{{ __('web.Approval status changed to Approved.') }}', 'bg-green-600');
                }
            })
            .catch(error => {
                alert('Error processing your request.');
            });
    });

    document.getElementById('reject-btn').addEventListener('click', function () {
        const itemId = @json($item->id);

        fetch(`/items/${itemId}/reject`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                admin_approval: false
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('approval-status').textContent =
                        '{{ __('web.Not Approved') }}';
                    showToast('{{ __('web.Approval status changed to Not Approved.') }}', 'bg-red-600');
                }
            })
            .catch(error => {
                alert('Error processing your request.');
            });
    });

    function showToast(message, bgColor) {
        const toastMessage = document.getElementById('toast-message');
        const toastText = document.getElementById('toast-text');

        toastText.textContent = message;

        toastMessage.classList.remove('bg-red-600', 'bg-green-600');
        toastMessage.classList.add(bgColor);

        // إزالة خاصية hidden وتهيئة الرسالة للظهور
        toastMessage.classList.remove('hidden');
        toastMessage.style.opacity = '1';
        toastMessage.style.transform = 'translateX(-50%) translateY(0)'; // حركة ظهور أفضل

        // إضافة تأثير زمني لإخفاء الرسالة بعد 4 ثواني
        setTimeout(() => {
            hideToast();
        }, 4000);
    }

    function hideToast() {
        const toastMessage = document.getElementById('toast-message');
        toastMessage.style.opacity = '0';
        toastMessage.style.transform = 'translateX(-50%) translateY(50px)';
        setTimeout(() => {
            toastMessage.classList.add('hidden');
        }, 500);
    }

    //images transform
    let currentIndex = 0;

    function moveCarousel(direction) {
        const images = document.getElementById("image-carousel");
        const totalImages = images.children.length;
        currentIndex = (currentIndex + direction + totalImages) % totalImages;
        const offset = -currentIndex * 144; // عرض كل صورة 144px (مع بعض المسافة بين الصور)

        // تغيير اتجاه التمرير بناءً على اللغة
        if (document.documentElement.lang === 'ar') {
            images.style.transform = `translateX(${-offset}px)`; // للغة العربية نمرر من اليمين لليسار
        } else {
            images.style.transform = `translateX(${offset}px)`; // للغة الإنجليزية نمرر من اليسار لليمين
        }
    }

</script>
@endsection