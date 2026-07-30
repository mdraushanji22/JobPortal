@extends(Auth::user()->role == 'employer' ? 'layouts.employer' : (Auth::user()->role == 'candidate' ? 'layouts.candidate' : 'layouts.app'))

@section('title', 'Messages')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-data="messaging()">
    <div class="lg:col-span-1 bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="p-4 border-b dark:border-gray-700">
            <h2 class="font-semibold">Conversations</h2>
        </div>
        <div class="divide-y dark:divide-gray-700" id="conversations">
            @forelse($conversations as $userId => $messages)
                @php $otherUser = $users->find($userId); @endphp
                @if($otherUser)
                <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer" @click="loadConversation({{ $userId }})">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-user text-gray-500 dark:text-gray-400"></i>
                        </div>
                        <div>
                            <p class="font-medium text-sm">{{ $otherUser->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $otherUser->email }}</p>
                        </div>
                    </div>
                </div>
                @endif
            @empty
                <div class="p-4 text-center text-gray-500 dark:text-gray-400 text-sm">No conversations yet.</div>
            @endforelse
        </div>
    </div>

    <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow flex flex-col h-[600px]">
        <div class="p-4 border-b dark:border-gray-700" id="chatHeader">
            <p class="text-gray-500 dark:text-gray-400 text-center">Select a conversation to start chatting</p>
        </div>
        <div class="flex-1 overflow-y-auto p-4 space-y-4" id="chatMessages">
        </div>
        <div class="p-4 border-t dark:border-gray-700">
            <form @submit.prevent="sendMessage" class="flex space-x-2">
                <input type="hidden" name="receiver_id" x-model="receiverId">
                <input type="text" x-model="messageText" placeholder="Type a message..." class="flex-1 px-4 py-2 border dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded focus:outline-none" required>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"><i class="fas fa-paper-plane"></i></button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function messaging() {
    return {
        receiverId: null,
        messageText: '',
        loadConversation(userId) {
            this.receiverId = userId;
            fetch('/messages/conversation/' + userId)
                .then(r => r.json())
                .then(messages => {
                    let html = '';
                    messages.forEach(msg => {
                        const isMine = msg.sender_id === {{ Auth::id() }};
                        html += `
                            <div class="flex ${isMine ? 'justify-end' : 'justify-start'}">
                                <div class="max-w-md p-3 rounded-lg ${isMine ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-700'}">
                                    <p>${msg.message || ''}</p>
                                    ${msg.file_path ? `<a href="/storage/${msg.file_path}" target="_blank" class="text-sm underline">View file</a>` : ''}
                                    <p class="text-xs mt-1 ${isMine ? 'text-blue-200' : 'text-gray-500 dark:text-gray-400'}">${new Date(msg.created_at).toLocaleString()}</p>
                                </div>
                            </div>
                        `;
                    });
                    document.getElementById('chatMessages').innerHTML = html;
                    document.getElementById('chatHeader').innerHTML = `<p class="font-semibold">${messages.length > 0 ? messages[0].sender.name : 'Chat'}</p>`;
                    document.getElementById('chatMessages').scrollTop = document.getElementById('chatMessages').scrollHeight;
                });
        },
        sendMessage() {
            if (!this.messageText.trim() || !this.receiverId) return;
            fetch('/messages/send', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
                body: JSON.stringify({ receiver_id: this.receiverId, message: this.messageText })
            }).then(r => r.json()).then(() => {
                this.messageText = '';
                this.loadConversation(this.receiverId);
            });
        }
    }
}
</script>
@endpush
@endsection
