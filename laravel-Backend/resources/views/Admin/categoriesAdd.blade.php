@extends('layout.master')
@section('body')
    <div class="bg-white text-gray-800 pb-6">
        <!-- Header -->

        <header class="bg-secondary text-white p-4">
            
            <div class="container mx-auto flex justify-between items-center">
                <h1 class="text-xl font-bold">{{__('web.Add Category')}}</h1>
                <a href="{{ LaravelLocalization::localizeUrl(route('categories.index')) }}" class="bg-white text-secondary font-semibold px-4 py-2 rounded hover:bg-beige">{{__('web.Back to Categories')}}</a>
            </div>
            
        </header>


        <!-- Main Content -->
        <main class="container mx-auto mt-10">
            <div class="max-w-lg mx-auto bg-beige p-6 rounded-lg shadow-lg">
                <h2 class="text-2xl font-bold text-primary mb-6 text-center">{{__('web.Add New Category')}}</h2>
                <form action="{{ route('categories.store') }}" method="post">
                    @csrf
                    

                    <!-- Category Name -->
                    <div class="mb-4">
                        <label for="category-name" class="block text-sm font-medium text-secondary mb-2">{{__('web.Category Name In English')}}</label>
                        <input type="text" id="category-name" name="en_name"
                            class="w-full p-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-secondary"
                            placeholder="Enter category name" required />
                    </div>

                    <!-- Category Ar_Name -->
                    <div class="mb-4">
                        <label for="category-name" class="block text-sm font-medium text-secondary mb-2">{{__('web.Category Name In Arabic')}}</label>
                        <input type="text" id="category-name" name="ar_name"
                            class="w-full p-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-secondary"
                            placeholder="Enter category name" required />
                    </div>

                    <!-- Category Description -->
                    <div class="mb-4">
                        <label for="category-description" class="block text-sm font-medium text-secondary mb-2">{{__('web.Category Description In English')}} </label>
                        <textarea id="category-description" name="en_descrieption" rows="4"
                            class="w-full p-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-secondary"
                            placeholder="Enter category description" required></textarea>
                    </div>

                    <!-- Category Ar_Description -->
                    <div class="mb-4">
                        <label for="category-description" class="block text-sm font-medium text-secondary mb-2">{{__('web.Category Description In Arabic')}}</label>
                        <textarea id="category-description" name="ar_description" rows="4"
                            class="w-full p-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-secondary"
                            placeholder="Enter category description" required></textarea>
                    </div>

                    <!-- Parent Category -->
                    <div class="mb-4">
                        <label for="parent-category" class="block text-sm font-medium text-secondary mb-2">{{__('web.Main Category')}}</label>
                        <select id="parent-category" name="parent_id"
                            class="w-full p-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-secondary">
                            <option value="">{{__('web.Select As Main Category')}}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->en_name }} - {{ $category->ar_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-between">
                        <button type="submit" class="bg-primary text-white py-2 px-6 rounded hover:bg-secondary">{{__('web.Add Category')}}</button>
                        <a href="/categories" class="bg-sky_blue text-black py-2 px-4 rounded hover:bg-gray-200">{{__('web.Cancel')}}</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
@endsection
