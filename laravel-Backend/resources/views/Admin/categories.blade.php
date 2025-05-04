@extends('layout.master')
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

@section('body')
    <div class="container w-full mx-auto bg-white p-6 shadow-md rounded-lg">



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

        
        <div class="text-white mt-2 p-4">
            <div class="container mx-auto flex justify-between items-center">
                <h1 class="text-2xl font-bold text-secondary">{{__('web.Categories')}}</h1>
                <a href="{{ route('categories.create') }}"
                    class="bg-white font-semibold text-secondary px-4 py-2 rounded hover:bg-beige">
                    {{__('web.+ Add Category')}}
                </a>



            </div>
        </div>
        <table
            class="w-full p-3 text-center border-separate border-spacing-y-3 rounded-lg border-2 border-gray-300 text-xs sm:text-sm">
            <thead>
                <tr class="bg-sky_blue">
                    <th class="px-4 py-2">{{__('web.ID')}}</th>
                    <th class="px-4 py-2">{{__('web.Name')}}</th>
                    <th class="px-4 py-2">{{__('web.Descrieption')}}</th>
                    <th class="px-4 py-2">{{__('web.Main Category')}}</th>
                    <th class="px-4 py-2">{{__('web.Procedures')}}</th>
                </tr>
            </thead>


            <tbody>
                @foreach ($categories as $category)
                    <tr class="hover:bg-beige">
                        <td class="px-4 py-2">{{ $category->id }}</td>
                        <!-- عرض الاسم بناءً على اللغة -->
                        <td class="px-4 py-2">{{ $category->name }}</td>
                        <!-- عرض الوصف بناءً على اللغة -->
                        <td class="px-4 py-2">{{ $category->description }}</td>


                        <!-- عرض اسم الفئة الرئيسية بناءً على اللغة -->
                        <td class="px-4 py-2">
                            @if($category->parent)
                                {{ $category->parent->name }}
                            @else
                                {{__('web.No thing')}}
                            @endif
                        </td>

                        <!-- إجراءات التعديل والحذف -->
                        <td class="px-4 py-2 flex justify-center gap-2">
                            <button onclick="showAlert({{ $category->id }})" class="hover:fill-red-700 p-2 rounded-full hover:bg-gray-400"
                                title="Delete">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="18" viewBox="0 0 14 18">
                                    <path id="ic_delete_24px"
                                        d="M6,19a2.006,2.006,0,0,0,2,2h8a2.006,2.006,0,0,0,2-2V7H6ZM19,4H15.5l-1-1h-5l-1,1H5V6H19Z"
                                        transform="translate(-5 -3)" />
                                </svg>
                            </button>
                            <a href="{{ route('categories.edit', $category->id) }}"
                                class="p-2 hover:fill-blue-700 rounded-full hover:bg-gray-400" title="Edit">
                                <svg  xmlns="http://www.w3.org/2000/svg" width="18.003" height="18.003"
                                    viewBox="0 0 18.003 18.003">
                                    <path id="ic_create_24px"
                                        d="M3,17.25V21H6.75L17.81,9.94,14.06,6.19ZM20.71,7.04a1,1,0,0,0,0-1.41L18.37,3.29a1,1,0,0,0-1.41,0L15.13,5.12l3.75,3.75,1.83-1.83Z"
                                        transform="translate(-3 -2.997)" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>

</div>
<!-- Alert Modal -->
<div id="deleteAlert" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg text-center w-80">
        <h3 class="text-primary text-lg font-semibold mb-4">{{__('web.Are you sure about the deletion process?')}}</h3>
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
        form.action = `/${lang}/categories/${id}`; // إضافة اللغة إلى الرابط الخاص بالحذف
        document.getElementById('deleteAlert').classList.remove('hidden');
    }

    function hideAlert() {
        document.getElementById('deleteAlert').classList.add('hidden');
    }
</script>

@endsection

