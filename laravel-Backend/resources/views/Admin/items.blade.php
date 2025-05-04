@extends('layout.master')

@section('body')
<div class="container w-auto mx-auto bg-white p-6 shadow-md rounded-lg">
    <h1 class="text-2xl font-bold text-secondary mb-4">{{ __('web.Products') }}</h1>

    <!-- عرض رسائل النجاح أو الخطأ -->
    @if (session('success') || session('error'))
        <div class="relative bg-{{ session('success') ? 'green-500' : 'red-500' }} text-white p-4 rounded-lg shadow-lg flex items-center justify-between max-w-full mb-4">
            <span class="flex-1">
                {{ session('success') ?? session('error') }}
            </span>
            <button onclick="this.parentElement.remove();" class="ml-4 text-xl font-bold hover:text-gray-300">
                &times;
            </button>
        </div>
    @endif

    <table
        class="w-full p-3 text-center border-separate border-spacing-y-3 rounded-lg border-2 border-gray-300 text-xs sm:text-sm">
        <thead>
            <tr class="bg-sky_blue">
                <th class="px-4 py-2">{{ __('web.ID') }}</th>
                <th class="px-4 py-2">{{ __('web.Customer Name') }}</th>
                <th class="px-4 py-2">{{ __('web.Product Name') }}</th>
                <th class="px-4 py-2">{{ __('web.Category') }}</th>
                {{-- <th class="px-4 py-2">{{ __('web.Descrieption') }}</th> --}}
                <th class="px-4 py-2">{{ __('web.Product Status') }}</th>
                <th class="px-4 py-2">{{ __('web.Product Location') }}</th>
<!--                 <th class="px-4 py-2">{{ __('web.Price per Hour') }}</th>
                <th class="px-4 py-2">{{ __('web.Price per Day') }}</th>
                <th class="px-4 py-2">{{ __('web.Assurance Amount') }}</th> -->
                <th class="px-4 py-2">{{ __('web.Quantity') }}</th>
                <th class="px-4 py-2">{{ __('web.Procedures') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr class="hover:bg-beige">
                    <td class="px-4 py-2">{{ $item->id }}</td>
                    <td class="px-4 py-2">{{ optional($item->user)->first_name }} {{ optional($item->user)->last_name }}</td>
                    <td class="px-4 py-2">{{ $item->name }}</td>
                    <td class="px-4 py-2">{{ html_entity_decode($item->category->name) }}</td>
                    {{-- <td class="px-4 py-2">{{ $item->description }}</td> --}}
                    <td class="px-4 py-2">{{ __('web.Product_Status.' . strtolower($item->status)) }}</td>
                    <td class="px-4 py-2">{{ $item->location }}</td>
<!--                     <td class="px-4 py-2">{{ $item->price_per_hour }}</td>
                    <td class="px-4 py-2">{{ $item->price_per_day }}</td>
                    <td class="px-4 py-2">{{ $item->price_assurance }}</td> -->
                    <td class="px-4 py-2">{{ $item->quantity }}</td>
                    <td class="px-4 py-2 flex justify-center gap-2">
                     
                        <a href="{{ route('items.show', $item->id) }}" 
                            class="bg-primary text-white rounded px-4 py-2 hover:bg-secondary">
                             {{ __('web.View More') }}
                         </a>
                         
                    
                        <td class="px-4 py-2 flex justify-center gap-2">
                                <!-- زر الحذف -->
              
                                    <button onclick="showAlert({{ $item->id }})" class="p-2 bg-red-500 text-white rounded hover:bg-red-600" type="submit">

                                    {{ __('web.Delete') }}

                                </button>

                            <!-- زر الإخفاء -->
                            <form action="{{ route('items.hide', $item->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button class="p-2 {{ $item->available ? 'bg-gray-500' : 'bg-green-500' }} text-white rounded hover:bg-gray-400" type="submit">
                                    {{ $item->available ? __('web.Not_available') : __('web.Available') }}
                                </button>
                            </form>
                        
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
   <!-- Alert Modal -->
   <div id="deleteAlert" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg text-center w-80">
        <h3 class="text-primary text-lg font-semibold mb-4">{{__('web.Are you sure you want to delete this Item?')}}</h3>
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
        form.action = `/${lang}/items/${id}`; // إضافة اللغة إلى الرابط الخاص بالحذف
        document.getElementById('deleteAlert').classList.remove('hidden');
    }

    function hideAlert() {
        document.getElementById('deleteAlert').classList.add('hidden');
    }
</script>
@endsection
