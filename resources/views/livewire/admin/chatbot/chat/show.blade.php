<div>
    <div x-data="chatShow" class="card card-flush" style="min-height: 75vh">
        <div x-show="loading" >
            <div class="position-absolute w-100 h-100 bg-white bg-opacity-10 d-flex align-items-center justify-content-center" style="z-index: 10; border-radius: 0.475rem;">
                <div class="text-center">
                    <div class="mb-4">
                        <span class="spinner-border spinner-border-lg text-primary" style="width: 3rem; height: 3rem;"></span>
                    </div>
                </div>
            </div>
        </div>

        @if($chatId)
            <div class="card-header">
                <div class="card-title">
                    <div class="d-flex justify-content-center flex-column me-3">
                        <a href="#" class="fs-4 fw-bold text-gray-900 text-hover-primary me-1 mb-2 lh-1">{{ $chat->user->name ?? __('Anonimous') }}</a>
                        <div class="mb-0 lh-1">
                            <span class="me-1"><i class="fa-light fa-envelope"></i></span>
                            <span class="fs-7 fw-semibold text-muted">{{ $chat->user->email ?? __('Anonimous') }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-toolbar">
                    <button wire:click="clearHistory" wire:loading.attr="disabled" wire:target="clearHistory" class="btn btn-sm btn-icon btn-active-light-primary me-1" title="Limpiar historial" type="button">
                        <span wire:loading.remove wire:target="clearHistory"><i class="fa-light fa-history fs-3"></i></span>
                        <span wire:loading wire:target="clearHistory" class="spinner-border spinner-border-sm text-primary"></span>
                    </button>
                    <button wire:click="deleteChat" wire:loading.attr="disabled" wire:target="deleteChat" class="btn btn-sm btn-icon btn-active-light-primary me-1" title="Eliminar chat" type="button">
                        <span wire:loading.remove wire:target="deleteChat"><i class="fa-light fa-trash fs-3"></i></span>
                        <span wire:loading wire:target="deleteChat" class="spinner-border spinner-border-sm text-primary"></span>
                    </button>
                </div>
            </div>
            <div class="card-body" id="kt_chat_messenger_body">
                <div class="scroll-y me-n5 pe-5 h-300px h-lg-auto" id="messages-container" style="max-height: 597px">
                    <div wire:loading wire:target="loadMore" class="text-center text-muted py-2">
                        <span class="spinner-border spinner-border-sm text-primary me-2"></span>
                        <span>{{ __('Loading more messages...') }}</span>
                    </div>
                    @forelse ($messages as $message)
                        <livewire:admin.chatbot.chat.message :chatbot="$chatbot" :chat="$chat" :user="$user" :message="$message" :key="$message['id']"/>
                    @empty
                        <div class="text-center text-muted py-10">{{ __('No messages found.') }}</div>
                    @endforelse
                </div>
            </div>
            <div class="card-footer pt-4">
                <form wire:submit.prevent="getAsk" x-ref="form" class="mt-4">
                    <div>
                        <textarea 
                            wire:model="ask" 
                            wire:loading.attr="disabled" 
                            wire:target="getAsk"
                            x-on:keydown.enter="handleEnter($event)"
                            id="chat-message-input"
                            class="form-control form-control-flush mb-3" 
                            placeholder="{{ __('Type your message') }}" 
                            rows="1"
                            required
                            autofocus>
                        </textarea>
                        @error('ask'){{ $message }}@enderror
                    </div>
                    <div class="d-flex flex-stack">
                        <div class="d-flex align-items-center me-2">
                            {{-- <button class="btn btn-sm btn-icon btn-active-light-primary me-1" type="button">
                                <i class="ki-outline ki-paper-clip fs-3"></i>
                            </button>
                            <button class="btn btn-sm btn-icon btn-active-light-primary me-1" type="button">
                                <i class="ki-outline ki-exit-up fs-3"></i>
                            </button> --}}
                        </div>
                        <button 
                            type="submit" 
                            wire:loading.attr="disabled" 
                            wire:target="getAsk" 
                            class="btn btn-primary">
                            <span>{{ __('Send') }}</span>
                            <div wire:loading wire:target="getAsk" class="loader"></div>
                        </button>
                    </div>
                </form>
            </div>
        @else
            <div class="card-body d-flex flex-column justify-content-center align-items-center" style="min-height: 500px;">
                <div class="text-center">
                    <div class="mb-9">
                        <div class="symbol symbol-100px mb-5">
                            <div class="symbol-label bg-light-primary">
                                <i class="ki-outline ki-message-text-2 fs-3x text-primary"></i>
                            </div>
                        </div>
                    </div>
                    
                    <h1 class="fw-bold text-gray-800 mb-3">{{ __('No Chat Selected') }}</h1>
                    <p class="text-gray-600 fs-5 fw-semibold mb-8">
                        {{ __('Choose a conversation from the list to start messaging') }}<br>
                        {{ __('or create a new chat to test the chatbot') }}
                    </p>
                    
                    @if(!$chatOwner)
                        <button wire:click="createChatOwn" wire:loading.attr="disabled" class="btn btn-primary">
                            <span wire:loading.remove wire:target="createChatOwn">
                                <i class="fa-light fa-plus fs-4 me-2"></i>
                                {{ __('Start New Chat') }}
                            </span>
                            <span wire:loading wire:target="createChatOwn">
                                <span class="spinner-border spinner-border-sm me-2"></span>
                                {{ __('Creating...') }}
                            </span>
                        </button>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>


@script
    <script>
        Alpine.data('chatShow', () => ({
            loading: false,
            hasMore: $wire.entangle('hasMore'),
            lastScrollHeight: 0,

            init(){
                KTComponents.init();
                
                window.addEventListener('select-chat', (event) => {
                    // console.log('select-chat: loading=true');
                    this.loading = true;
                });
                window.addEventListener('selected-chat', () => {
                    // console.log('selected-chat: loading=false');
                    this.loading = false;
                    this.scrollListenerToTop();
                    this.scrollToBottom();
                });
                window.addEventListener('message-success', () => {
                    // console.log('message-success');
                    this.scrollToBottom();
                    this.inputFocus();
                });
                window.addEventListener('history-loaded', () => {
                    // console.log('history-loaded');
                    this.scrollRestorePosition();
                });                
                window.addEventListener('scroll-to-bottom', () => {
                    // console.log('scroll-to-bottom by alpine js');
                    this.scrollToBottom();
                });
                
            },
            scrollListenerToTop() {
                const container = document.getElementById('messages-container');
                container.addEventListener('scroll', () => {
                    if (container.scrollTop === 0) {
                        this.loadMoreHistory();
                    }
                });
            },
            loadMoreHistory() {
                if(!this.hasMore) return;
                const container = document.getElementById('messages-container');
                this.lastScrollHeight = container.scrollHeight;
                // console.log('loadMoreHistory: lastScrollHeight =' + this.lastScrollHeight);
                @this.call('loadMore');
            },
            scrollToBottom() {
                // this.$nextTick(() => {
                    // console.log('scrollToBottom');
                    const messagesContainer = document.getElementById('messages-container');
                    if (messagesContainer) {
                        messagesContainer.scrollTo({
                            top: messagesContainer.scrollHeight,
                            behavior: 'smooth'
                        });
                    }
                // });
            },
            scrollRestorePosition() {
                // console.log('scrollRestorePosition: lastScrollHeight = ' + this.lastScrollHeight);
                const container = document.getElementById('messages-container');
                container.scrollTop = container.scrollHeight - this.lastScrollHeight; // Mantiene misma posición después de insertar arriba
            },
            inputFocus() {
                // console.log('inputFocus');
                const input = document.getElementById('chat-message-input');
                if (input) {
                    input.focus();
                }
            },
            handleEnter(event) {
                if (event.shiftKey) {
                    return;
                }
                event.preventDefault();
                this.$refs.form.dispatchEvent(new Event('submit', { cancelable: true }));
            },
        }));
    </script>
@endscript