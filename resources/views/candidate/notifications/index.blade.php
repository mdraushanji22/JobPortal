@extends('layouts.candidate')

@section('title', 'Notifications')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold dark:text-white">Notifications</h1>
        <div class="flex space-x-2">
            <a href="{{ route('candidate.notifications.index', ['unread' => 1]) }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Unread</a>
            <a href="{{ route('candidate.notifications.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">All</a>
            <form action="{{ route('candidate.notifications.read-all') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Mark All Read</button>
            </form>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow divide-y divide-gray-200 dark:divide-gray-600">
        @forelse($notifications as $notification)
        <div class="p-4 flex items-start {{ $notification->is_read ? 'opacity-60' : 'bg-blue-50 dark:bg-blue-900/20' }}">
            <div class="p-3 mr-4 rounded-full {{ $notification->is_read ? 'bg-gray-100 dark:bg-gray-700' : 'bg-blue-100 dark:bg-blue-900' }}">
                <i class="fas fa-envelope-open-text {{ $notification->is_read ? 'text-gray-500' : 'text-blue-600 dark:text-blue-300' }}"></i>
            </div>
            <div class="flex-1">
                <h3 class="font-semibold dark:text-white">{{ $notification->title }}</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $notification->message }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
            </div>
            @unless($notification->is_read)
            <form action="{{ route('candidate.notifications.read', $notification) }}" method="POST">
                @csrf
                <button type="submit" class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">Open</button>
            </form>
            @else
                @if(is_array($notification->data) && isset($notification->data['url']))
                <a href="{{ $notification->data['url'] }}" class="px-3 py-1 text-sm border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-50 dark:hover:bg-gray-700">View</a>
                @endif
            @endunless
        </div>
        @empty
        <div class="p-10 text-center text-gray-500 dark:text-gray-400">
            <i class="fas fa-bell-slash text-4xl mb-3 block"></i>
            No notifications yet.
        </div>
        @endforelse
    </div>
    <div class="mt-4">{{ $notifications->links() }}</div>
</div>
@endsection
