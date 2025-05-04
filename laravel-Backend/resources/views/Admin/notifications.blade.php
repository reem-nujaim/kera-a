@extends('layout.master')

@section('body')
<div class="container mx-auto p-6">
    <h2 class="text-2xl text-secondary font-bold mb-4">{{__('web.Notifications')}}</h2>

    <div class="flex justify-between mb-4">
        <a href="{{ route('notifications.markAllAsRead') }}" class="bg-primary hover:bg-secondary text-white px-4 py-2 rounded">
            {{__('web.Mark all as read')}}
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-4">
        @if ($notifications->count())
            <ul class="divide-y divide-gray-200">
                @foreach ($notifications as $notification)
                    <li class="p-4 flex justify-between items-center
                               {{ $notification->read_at ? 'bg-beige' : 'bg-sky_blue' }}">
                        <a href="{{ route('notifications.redirect', $notification->id) }}"
                           class="block w-full">
                            <p>{{ $notification->title ?? 'إشعار جديد' }}</p>
                            <p>{{ $notification->message ?? 'إشعار جديد' }}</p>
                            <div class="text-sm text-gray-500">{{ $notification->created_at->diffForHumans() }}</div>
                        </a>
                        @if (!$notification->read_at)
                            <span class="text-blue-500 text-sm text-nowrap">{{__('web.Unread')}}</span>
                        @else
                            <span class="text-green-500 text-sm"> ✓{{__('web.Read')}}</span>
                        @endif
                    </li>
                @endforeach
            </ul>
            <div class="mt-4">
                {{ $notifications->links() }}
            </div>
        @else
            <p class="text-gray-500">{{__('web.Notification not found')}}</p>
        @endif
    </div>
</div>
@endsection
