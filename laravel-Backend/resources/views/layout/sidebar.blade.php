<div x-cloak :class="sidebarOpen ? 'block' : 'hidden'" @click="sidebarOpen = false"
    class="fixed inset-0 z-20 transition-opacity bg-black opacity-50 lg:hidden"></div>
<div x-cloak :class="sidebarOpen? 'translate-x-0 ease-out': (document.documentElement.dir === 'rtl' ? 'translate-x-full ease-in' : '-translate-x-full ease-in')"
class="fixed inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-0' : 'left-0' }} z-30 w-64 overflow-y-auto transition duration-300 transform lg:translate-x-0 lg:static lg:inset-0 navigation pt-3 bg-primary m-1 rounded-2xl">
    <div class="flex items-center justify-center m-2 p-2 border-b-2 border-secondary">
        <div class="flex items-center">
             @php
                $settings = \App\Models\Setting::first(); // جلب إعدادات التطبيق
            @endphp

            <img
                class="h-10 w-10 object-cover rounded-full"
                src="{{ $settings && $settings->logo ? asset('storage/' . $settings->logo) : 'https://via.placeholder.com/100' }}"
                alt="App Logo">

            <div class="title text-beige text-2xl font-bold p-2">{{__('web.KERA\'A')}}</div>
        </div>
    </div>
    <div class="links">
        <a class="link" href="{{ LaravelLocalization::localizeUrl(route('dashboard')) }}">
            <div class="icon text-xl text-beige"><i class='bx bx-home'></i></div>
            <div class="text mx-3">{{__('web.Home')}}</div>
        </a>
        <a class="link" href="{{ LaravelLocalization::localizeUrl(route('reports.index')) }}">
            <div class="icon text-xl text-beige"><i class='bx bx-file text-lg mr-2'></i></div>
            <div class="text mx-3">{{ __('web.reports') }}</div>
        </a>
        <a class="link" href="{{ LaravelLocalization::localizeUrl('/items') }}">
            <div class="icon text-xl text-beige"><i class='bx bxs-shopping-bag '></i></div>
            <div class="text mx-3">{{__('web.Products')}}</div>
        </a>
        <a class="link" href="{{ LaravelLocalization::localizeUrl('/categories') }}">
            <div class="icon text-xl text-beige"><i class='bx bxs-category'></i></div>
            <div class="text mx-3">{{__('web.Categories')}}</div>
        </a>
        <a class="link" href="{{ LaravelLocalization::localizeUrl('/customers') }}">
            <div class="icon text-xl text-beige"><i class='bx bxs-user-detail'></i></div>
            <div class="text mx-3">{{__('web.Customers')}}</div>
        </a>
        <a class="link" href="{{ LaravelLocalization::localizeUrl('/requests') }}">
            <div class="icon text-xl text-beige"><i class='bx bx-mail-send'></i></div>
            <div class="text mx-3">{{__('web.Requests')}}</div>
        </a>
        <a class="link" href="{{ LaravelLocalization::localizeUrl('/assurances') }}">
            <div class="icon text-xl text-beige"><i class='bx bx-money-withdraw'></i></div>
            <div class="text mx-3">{{__('web.Assurances')}}</div>
        </a>
        <a class="link" href="{{ LaravelLocalization::localizeUrl('/ratings') }}">
            <div class="icon text-xl text-beige"><i class='bx bx-chat'></i></div>
            <div class="text mx-3">{{__('web.Ratings')}}</div>
        </a>
        <a class="link" href="{{ LaravelLocalization::localizeUrl('/lateFees') }}">
            <div class="icon text-xl text-beige"><i class='bx bx-timer'></i></div>
            <div class="text mx-3">{{__('web.Late Fees')}}</div>
        </a>
        <a class="link" href="{{ LaravelLocalization::localizeUrl('/log') }}">
            <div class="icon text-xl text-beige"><i class='bx bxs-report'></i></div>
            <div class="text mx-3">{{__('web.Transactions Log')}}</div>
        </a>
        <a class="link" href="{{ LaravelLocalization::localizeUrl('/bills') }}">
            <div class="icon text-xl text-beige"><i class='bx bx-receipt'></i></div>
            <div class="text mx-3">{{__('web.Bills')}}</div>
        </a>
        <div class="title text-beige font-semibold text-lg mx-3">{{__('web.Account')}}</div>
        <a class="link" href="{{ LaravelLocalization::localizeUrl('/profile') }}">
            <div class="icon text-xl text-beige"><i class='bx bx-user-circle'></i></div>
            <div class="text mx-3">{{__('web.My profile')}}</div>
        </a>
        <a class="link" href="{{ LaravelLocalization::localizeUrl('/setting') }}">
            <div class="icon text-xl text-beige"><i class='bx bx-cog'></i></div>
            <div class="text mx-3">{{__('web.Setting')}}</div>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <a class="link" href="{{ route('logout') }}" onclick="event.preventDefault();
                                        this.closest('form').submit();">
                <div class="icon text-xl text-beige"><i class='bx bx-log-out'></i></div>
                <div class="text mx-3 text-white font-bold">{{__('web.Log Out')}}</div>
            </a>
        </form>
        <div class="flex flex-nowrap bottom-0 items-center justify-end p-4 m-2 border-t-2 border-secondary">
            <div class="icon text-xl text-beige"><i class='bx bxs-phone-call'></i></div>
            <div class="title mx-3 text-white text-md p-2 font-bold">{{__('web.Tecnical Support Numbers')}} :
                <br>
                <span class=" text-sm">+777777777</span>
                <span class=" text-sm">+787777777</span>
            </div>
        </div>
    </div>
</div>