@extends('layout.master')

@section('body')
<div class="container w-auto mx-auto bg-white p-6 shadow-md rounded-lg">
    <h1 class="text-2xl font-bold text-secondary mb-4">{{ __('web.Ratings') }}</h1>

    <!-- عرض رسائل النجاح أو الخطأ -->
    @if (session('success') || session('error'))
        <div
            class="relative bg-{{ session('success') ? 'green-500' : 'red-500' }} text-white p-4 rounded-lg shadow-lg flex items-center justify-between max-w-full mb-4">
            <span class="flex-1">
                {{ session('success') ?? session('error') }}
            </span>
            <button onclick="this.parentElement.style.display='none'" class="ml-2">
                <span class="text-xl">&times;</span>
            </button>
        </div>
    @endif

    <table
        class="w-full p-3 text-center border-separate border-spacing-y-3 rounded-lg border-2 border-gray-300 text-xs sm:text-sm text-nowrap">
        <thead>
            <tr class="bg-sky_blue">
                <th class="px-4 py-2">{{ __('web.ID') }}</th>
                <th class="px-4 py-2">{{ __('web.Customer Name') }}</th>
                <th class="px-4 py-2">{{ __('web.Product Name') }}</th>
                <th class="px-4 py-2">{{ __('web.Score') }}</th>
                <th class="px-4 py-2">{{ __('web.Comment') }}</th>
                <th class="px-4 py-2">{{ __('web.Review Date') }}</th>
                <th class="px-4 py-2">{{ __('web.Procedures') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ratings as $rating)
                <tr class="hover:bg-beige">
                    <td class="px-4 py-2">{{ $rating->id }}</td>
                    <td class="px-4 py-2">
                        {{ $rating->user ? $rating->user->first_name : 'N/A' }}
                        {{ $rating->user ? $rating->user->last_name : 'N/A' }}
                    </td>
                    <td class="px-4 py-2">{{ $rating->item ? $rating->item->name : 'N/A' }}</td>
                    <td class="px-4 py-2">{{ $rating->score }}</td>
                    <td class="px-4 py-2 text-wrap">
                        <!-- عرض النص المحدود أولاً -->
                        <span id="comment-{{ $rating->id }}" class="comment-text" data-full-comment="{{ $rating->comment }}"
                            data-is-truncated="true">
                            {{ Str::limit($rating->comment, 50, '...') }} <!-- تأكد من إضافة النقاط -->
                        </span>
                        @if (strlen($rating->comment) > 50)
                            <button class="text-blue-500" onclick="toggleComment({{ $rating->id }})"
                                id="toggle-button-{{ $rating->id }}">
                                {{ __('web.Show more') }}
                            </button>
                        @endif
                    </td>
                    <td class="px-4 py-2">{{ $rating->review_date }}</td>
                    <td class="px-4 py-2 flex justify-center gap-2">
                        <!-- زر الحذف -->
                        <button onclick="showAlert({{ $rating->id }})"
                            class="p-2 bg-red-500 text-white rounded hover:bg-red-600" type="submit">
                            {{ __('web.Delete') }}
                        </button>


                        <!-- زر الإخفاء -->
                        <form action="{{ route('ratings.hide', $rating->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button
                                class="p-2 {{ $rating->hidden ? 'bg-gray-500' : 'bg-green-500' }} text-white rounded hover:bg-gray-400"
                                type="submit">
                                {{ $rating->hidden ? __('web.Unhide') : __('web.Hide') }}
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
        <h3 class="text-primary text-lg font-semibold mb-4">{{__('web.Are you sure you want to delete this rating?')}}
        </h3>
        <form id="deleteForm" method="POST" action="">
            @csrf
            @method('DELETE')
            <div class="flex justify-between">
                <button type="submit"
                    class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary">{{__('web.Yes')}}</button>
                <button type="button" onclick="hideAlert()"
                    class="bg-sky_blue px-4 py-2 rounded-lg hover:bg-gray-200">{{__('web.Cancel')}}</button>
            </div>
        </form>
    </div>
</div>


<script>
    function showAlert(id) {
        const form = document.getElementById('deleteForm');
        const lang = '{{ app()->getLocale() }}'; // جلب اللغة الحالية من السيرفر
        form.action = `/${lang}/ratings/${id}`; // إضافة اللغة إلى الرابط الخاص بالحذف
        document.getElementById('deleteAlert').classList.remove('hidden');
    }

    function hideAlert() {
        document.getElementById('deleteAlert').classList.add('hidden');
    }

    function toggleComment(id) {
        const commentText = document.getElementById(`comment-${id}`);
        const toggleButton = document.getElementById(`toggle-button-${id}`);
        const fullComment = commentText.getAttribute('data-full-comment');
        const isTruncated = commentText.getAttribute('data-is-truncated') === 'true';

        if (isTruncated) {
            // إذا كان النص مقصوصًا، اعرض النص الكامل
            commentText.textContent = fullComment;
            commentText.setAttribute('data-is-truncated', 'false');
            toggleButton.textContent = '{{ __('web.Show less') }}'; // غيّر النص إلى "عرض أقل"
        } else {
            // إذا كان النص كاملاً، اعرض النص المحدود
            commentText.textContent = fullComment.slice(0, 50) + '...';
            commentText.setAttribute('data-is-truncated', 'true');
            toggleButton.textContent = '{{ __('web.Show more') }}'; // غيّر النص إلى "عرض المزيد"
        }
    }


</script>
@endsection
