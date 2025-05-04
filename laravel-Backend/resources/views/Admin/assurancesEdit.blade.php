@extends('layout.master')

@section('body')
<div class="bg-white pb-6">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-secondary text-white p-4">
            <div class="container mx-auto flex justify-between items-center">
                <h1 class="text-xl font-bold">{{__('web.Edit Assurance')}}</h1>
                <a href="{{ route('assurances.index') }}" class="bg-white text-secondary font-semibold px-4 py-2 rounded hover:bg-beige">
                    {{__('web.Back to Assurances')}}</a>
            </div>
        </header>

        <!-- Main Content -->
        <main class="container mx-auto mt-10">
            <div class="max-w-lg mx-auto bg-beige p-6 rounded-lg shadow-lg">
                <h2 class="text-2xl text-primary font-bold mb-6 text-center">{{__('web.Edit Assurance')}}</h2>
                <form action="{{ route('assurances.update', $assurance->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Request Number Dropdown -->
                    <div class="mb-4">
                        <label for="request_number" class="block text-secondary font-medium mb-2">{{__('web.Request ID')}}</label>
                        <select id="request_number" name="request_number" class="w-full p-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-secondary" onchange="updateItemNameAndCustomerName()">
                            <option value="{{ $assurance->rental_request_id }}">{{__('web.Request')}} #{{ $assurance->rental_request_id }}</option>
                            @foreach ($requests as $request)
                                <option value="{{ $request->id }}"
                                        data-item-name="{{ $request->item->name }}"
                                        data-customer-name="{{ $request->user->first_name }} {{ $request->user->last_name }}"
                                        data-item-id="{{ $request->item->id }}">
                                    {{__('web.Request')}} #{{ $request->id }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Item Name (Auto-filled based on Request Number) -->
                    <div class="mb-4">
                        <label for="item_name" class="block text-secondary font-medium mb-2">{{__('web.Product Name')}}</label>
                        <input type="text" id="item_name" name="item_name" value="{{ $assurance->item->name }}"
                               class="w-full p-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-secondary" readonly>
                    </div>

                    <!-- Customer Name (Auto-filled based on Request Number) -->
                    <div class="mb-4">
                        <label for="customer_name" class="block text-secondary font-medium mb-2">{{__('web.Customer Name')}}</label>
                        <input type="text" id="customer_name" name="customer_name" value="{{ $assurance->rental_request->user->first_name }} {{ $assurance->rental_request->user->last_name }}"
                               class="w-full p-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-secondary" readonly>
                    </div>

                    <!-- Hidden fields for item_id and rental_request_id -->
                    <input type="hidden" id="item_id" name="item_id" value="{{ $assurance->item_id }}">
                    <input type="hidden" id="rental_request_id" name="rental_request_id" value="{{ $assurance->rental_request_id }}">

                    <!-- Amount -->
                    <div class="mb-4">
                        <label for="amount" class="block text-secondary font-medium mb-2">{{__('web.Assurance Amount')}}</label>
                        <input type="number" id="amount" name="amount" value="{{ $assurance->amount }}"
                               class="w-full p-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-secondary" placeholder="Enter Amount">
                    </div>

                    <!-- English Description -->
                    <div class="mb-4">
                        <label for="en_description" class="block text-secondary font-medium mb-2">{{__('web.Assurance Description In English')}}</label>
                        <textarea id="en_description" name="en_description" rows="4"
                                  class="w-full p-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-secondary"
                                  placeholder="Enter English Description">{{$assurance->en_description}}</textarea>
                    </div>

                    <!-- Arabic Description -->
                    <div class="mb-4">
                        <label for="ar_description" class="block text-secondary font-medium mb-2">{{__('web.Assurance Description In Arabic')}}</label>
                        <textarea id="ar_description" name="ar_description" rows="4"
                                  class="w-full p-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-secondary"
                                  placeholder="أدخل الوصف بالعربية">{{$assurance->ar_description}}</textarea>
                    </div>

                    {{-- <!-- Date -->
                    <div class="mb-4">
                        <label for="date" class="block text-secondary font-medium mb-2">Date</label>
                        <input type="datetime-local" id="date" name="date" value="{{ \Carbon\Carbon::parse($assurance->date)->format('Y-m-d\TH:i') }}"
                               class="w-full p-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-secondary">
                    </div> --}}

                    <!-- Submit Button -->
                    <div class="flex justify-between">
                        <button type="submit" class="bg-primary text-white py-2 px-6 rounded hover:bg-secondary">{{__('web.Save Changes')}}</button>
                        <a href="{{ route('assurances.index') }}" class="bg-sky_blue text-black py-2 px-4 rounded hover:bg-gray-200">{{__('web.Cancel')}}</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

<script>
    function updateItemNameAndCustomerName() {
        var requestNumber = document.getElementById('request_number');
        var itemName = document.getElementById('item_name');
        var customerName = document.getElementById('customer_name');
        var itemId = document.getElementById('item_id');
        var rentalRequestId = document.getElementById('rental_request_id');

        // Get the selected option's data attributes for item name and customer name
        var selectedOption = requestNumber.options[requestNumber.selectedIndex];

        // Check if there's a valid selection
        if (selectedOption.value) {
            var itemNameValue = selectedOption.getAttribute('data-item-name');
            var customerNameValue = selectedOption.getAttribute('data-customer-name');
            var itemIdValue = selectedOption.getAttribute('data-item-id');
            var rentalRequestIdValue = selectedOption.value;

            // Update the item name and customer name fields
            itemName.value = itemNameValue;
            customerName.value = customerNameValue;
            itemId.value = itemIdValue; // Set item id
            rentalRequestId.value = rentalRequestIdValue; // Set rental request id
        } else {
            itemName.value = ''; // Clear the item name field if no request is selected
            customerName.value = ''; // Clear the customer name field if no request is selected
            itemId.value = ''; // Clear the item id field if no request is selected
            rentalRequestId.value = ''; // Clear the rental request id field if no request is selected
        }
    }
</script>

@endsection
