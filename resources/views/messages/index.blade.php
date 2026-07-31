@extends(Auth::user()->role == 'employer' ? 'layouts.employer' : (Auth::user()->role == 'candidate' ? 'layouts.candidate' : 'layouts.app'))

@section('title', 'Messages')

@section('content')
<style>[x-cloak]{display:none!important}</style>
@php $initialConversation = request()->integer('conversation') ?: null; @endphp
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-data="messaging({{ $initialConversation }})" @preview-image.window="previewImage = $event.detail.src">
    <div class="lg:col-span-1 bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="p-4 border-b dark:border-gray-700">
            <h2 class="font-semibold">Conversations</h2>
        </div>
        <div class="divide-y dark:divide-gray-700" id="conversations">
            @forelse($conversations as $conversation)
                @php
                    $isEmployer = Auth::user()->isEmployer();
                    $otherUser = $isEmployer ? $conversation->application->candidate->user : $conversation->application->jobListing->employer->user;
                    $lastMessage = $conversation->lastMessage;
                    $lastPreview = $lastMessage
                        ? ($lastMessage->message ?: ($lastMessage->is_image ? '[Image]' : ($lastMessage->original_file_name ? '[File] ' . $lastMessage->original_file_name : 'Attachment')))
                        : null;
                @endphp
                <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer" @click="loadConversation({{ $conversation->id }})">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-user text-gray-500 dark:text-gray-400"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <p class="font-medium text-sm truncate">{{ $otherUser->name }}</p>
                                @if($conversation->unread_count > 0)
                                    <span class="bg-blue-600 text-white text-xs px-2 py-0.5 rounded-full">{{ $conversation->unread_count }}</span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $conversation->jobTitle() }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $lastPreview ?? 'No messages yet' }}</p>
                        </div>
                    </div>
                </div>
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
            <form @submit.prevent="sendMessage">
                <p x-show="errorText" x-cloak x-text="errorText" class="text-red-600 dark:text-red-400 text-xs mb-1"></p>
                <div x-show="pendingFile" x-cloak class="mb-2 flex items-center justify-between bg-gray-100 dark:bg-gray-700 rounded px-3 py-2 text-sm">
                    <span class="truncate"><i class="fas fa-paperclip mr-1"></i><span x-text="pendingFile ? pendingFile.name : ''"></span></span>
                    <button type="button" @click="clearPendingFile()" class="text-red-500 ml-2" title="Remove"><i class="fas fa-times"></i></button>
                </div>
                <div class="flex space-x-2">
                    <input type="hidden" name="conversation_id" x-model="conversationId">
                    <input type="file" x-ref="fileInput" accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx,.xls,.xlsx,.txt,.zip" class="hidden" @change="onFileSelected($event)">
                    <button type="button" @click="$refs.fileInput.click()" class="px-3 py-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 border dark:border-gray-600 rounded" title="Attach file"><i class="fas fa-paperclip"></i></button>
                    <input type="text" x-model="messageText" placeholder="Type a message..." class="flex-1 px-4 py-2 border dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 dark:placeholder-gray-400 rounded focus:outline-none">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700" title="Send"><i class="fas fa-paper-plane"></i></button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="previewImage" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75" @click="previewImage = null">
        <div class="max-w-3xl max-h-full p-4" @click.stop>
            <img :src="previewImage" alt="Image preview" class="max-w-full max-h-[80vh] rounded shadow-lg">
            <div class="mt-2 text-center">
                <a :href="previewImage" target="_blank" class="text-blue-400 hover:text-blue-300 text-sm"><i class="fas fa-external-link-alt mr-1"></i>Open in new tab</a>
            </div>
        </div>
        <button @click="previewImage = null" class="absolute top-4 right-4 w-10 h-10 rounded-full bg-gray-800 bg-opacity-75 text-white text-xl flex items-center justify-center hover:bg-gray-700" title="Close"><i class="fas fa-times"></i></button>
    </div>
</div>

@push('scripts')
<script>
function messaging(initialConversationId = null) {
    return {
        conversationId: initialConversationId,
        messageText: '',
        pendingFile: null,
        errorText: '',
        previewImage: null,
        polling: null,
        init() {
            if (this.conversationId) {
                this.loadConversation(this.conversationId);
            }
        },
        loadConversation(id) {
            this.conversationId = id;
            this.errorText = '';
            if (this.polling) clearInterval(this.polling);
            this.fetchConversation(id);
            this.polling = setInterval(() => this.fetchConversation(id), 3000);
        },
        fetchConversation(id) {
            fetch('/messages/conversation/' + id)
                .then(r => {
                    if (!r.ok) throw new Error('Failed to load conversation');
                    return r.json();
                })
                .then(data => {
                    const me = {{ Auth::id() }};
                    const messages = data.messages || [];
                    let html = '';
                    messages.forEach(msg => {
                        const isMine = msg.sender_id === me;
                        const isImage = msg.is_image && msg.attachment_url;
                        const isFile = msg.file_path && msg.download_url && !isImage;

                        if (isImage) {
                            html += `
                                <div class="flex ${isMine ? 'justify-end' : 'justify-start'}">
                                    <div class="max-w-xs">
                                        <img src="${msg.attachment_url}" alt="${escapeHtml(msg.original_file_name || 'Image')}" loading="lazy" class="rounded-lg shadow cursor-pointer" onclick="window.dispatchEvent(new CustomEvent('preview-image', { detail: { src: '${msg.attachment_url}' } }))">
                                        <p class="text-xs mt-1 ${isMine ? 'text-blue-200 text-right' : 'text-gray-500 dark:text-gray-400'}">${new Date(msg.created_at).toLocaleString()}</p>
                                    </div>
                                </div>
                            `;
                        } else if (isFile) {
                            html += `
                                <div class="flex ${isMine ? 'justify-end' : 'justify-start'}">
                                    <div class="max-w-sm p-3 rounded-lg ${isMine ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-700'}">
                                        <div class="flex items-center gap-3">
                                            <i class="fas ${fileIcon(msg.file_type)} text-2xl ${isMine ? 'text-blue-200' : 'text-gray-500 dark:text-gray-400'}"></i>
                                            <div class="min-w-0 flex-1">
                                                <p class="font-medium text-sm truncate">${escapeHtml(msg.original_file_name || 'Attachment')}</p>
                                                <p class="text-xs ${isMine ? 'text-blue-200' : 'text-gray-500 dark:text-gray-400'}">
                                                    ${msg.formatted_file_size ? msg.formatted_file_size : ''}${msg.file_type ? ' · ' + escapeHtml(msg.file_type.toUpperCase()) : ''} · ${new Date(msg.created_at).toLocaleString()}
                                                </p>
                                            </div>
                                            <a href="${msg.download_url}" class="shrink-0 ${isMine ? 'text-blue-200 hover:text-white' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'}"><i class="fas fa-download"></i></a>
                                        </div>
                                    </div>
                                </div>
                            `;
                        } else {
                            html += `
                                <div class="flex ${isMine ? 'justify-end' : 'justify-start'}">
                                    <div class="max-w-md p-3 rounded-lg ${isMine ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-700'}">
                                        <p>${escapeHtml(msg.message || '')}</p>
                                        <p class="text-xs mt-1 ${isMine ? 'text-blue-200' : 'text-gray-500 dark:text-gray-400'}">${new Date(msg.created_at).toLocaleString()}</p>
                                    </div>
                                </div>
                            `;
                        }
                    });
                    const chatMessages = document.getElementById('chatMessages');
                    if (chatMessages) chatMessages.innerHTML = html;
                    const chatHeader = document.getElementById('chatHeader');
                    if (chatHeader) {
                        const conv = data.conversation || {};
                        const otherName = escapeHtml((conv.other_user && conv.other_user.name) || 'Chat');
                        const jobTitle = escapeHtml(conv.job_title || '');
                        chatHeader.innerHTML = `<p class="font-semibold">${otherName}</p>` + (jobTitle ? `<p class="text-xs text-gray-500 dark:text-gray-400">${jobTitle}</p>` : '');
                    }
                    if (chatMessages) chatMessages.scrollTop = chatMessages.scrollHeight;
                })
                .catch(err => console.error('Conversation load failed:', err));
        },
        onFileSelected(event) {
            this.pendingFile = event.target.files[0] || null;
        },
        clearPendingFile() {
            this.pendingFile = null;
            if (this.$refs.fileInput) this.$refs.fileInput.value = '';
        },
        sendMessage() {
            const text = this.messageText.trim();
            if ((!text && !this.pendingFile) || !this.conversationId) return;
            this.errorText = '';
            const formData = new FormData();
            formData.append('conversation_id', this.conversationId);
            if (text) formData.append('message', text);
            if (this.pendingFile) formData.append('file', this.pendingFile);

            fetch('/messages/send', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: formData
            })
            .then(r => r.json().then(data => ({ ok: r.ok, status: r.status, data })))
            .then(({ ok, data }) => {
                if (!ok) {
                    let message = data.error || 'Failed to send message.';
                    if (data.errors) {
                        const first = Object.values(data.errors)[0];
                        if (Array.isArray(first) && first[0]) message = first[0];
                    }
                    this.errorText = message;
                    throw new Error(message);
                }
                this.messageText = '';
                this.clearPendingFile();
                this.loadConversation(this.conversationId);
            })
            .catch(err => console.error('Message send failed:', err));
        },
        destroy() {
            if (this.polling) clearInterval(this.polling);
        }
    }
}

function fileIcon(ext) {
    const e = (ext || '').toLowerCase();
    if (e === 'pdf') return 'fa-file-pdf';
    if (e === 'doc' || e === 'docx') return 'fa-file-word';
    if (e === 'xls' || e === 'xlsx') return 'fa-file-excel';
    if (e === 'zip') return 'fa-file-archive';
    if (e === 'txt') return 'fa-file-alt';
    if (['jpg', 'jpeg', 'png', 'webp'].includes(e)) return 'fa-file-image';
    return 'fa-file';
}

function escapeHtml(value) {
    if (value === null || value === undefined) return '';
    return String(value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}
</script>
@endpush
@endsection
