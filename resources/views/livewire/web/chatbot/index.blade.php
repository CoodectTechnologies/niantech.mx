@assets
    <link rel="stylesheet" href="{{ asset('assets/web/chatbot/css/chatbot.css') }}">
@endassets

<div>
	<div x-data='chat' class="chatbox-wrapper">
		@if($chatbot)
			<div class="chatbox-toggle">
				<img style="width: 40px" src="{{ asset(config('app.logo_favicon')) }}" alt="Chatbot">
			</div>
			<div wire:ignore.self class="chatbox-message-wrapper">
				<div class="chatbox-message-header">
					<div class="chatbox-message-profile">
						<img src="{{ $chatbot->imagePreview() }}" alt="" class="chatbox-message-image">
						<div>
							<h4 class="chatbox-message-name">CHATBOT</h4>
							<p class="chatbox-message-status">{{ $chatbot->name }}</p>
						</div>
					</div>
					{{-- <div class="chatbox-message-dropdown">
						<i class='fa-light fa-ellipsis-vertical chatbox-message-dropdown-toggle'></i>
						<ul class="chatbox-message-dropdown-menu">
							<li>
								<a href="#">Search</a>
							</li>
							<li>
								<a href="#">Report</a>
							</li>
						</ul>
					</div> --}}
				</div>
				<div wire:loading wire:target="loadMore" class="text-center text-muted py-2">
					<span class="spinner-border spinner-border-sm text-primary me-2"></span>
					<span>{{ __('Loading more messages...') }}</span>
				</div>
				<div class="chatbox-message-content" id="chatbox-message-content">
					@forelse($messages as $message)
						<livewire:web.chatbot.message :chatbot="$chatbot" :chat="$chat" :user="$user" :message="$message" :key="$message['id']"/>
					@empty
						<h4 class="chatbox-message-no-message">{{ __("You don't have message yet!") }}</h4>
						{{-- <div class="text-center text-muted py-10">{{ __('No messages found.') }}</div> --}}
					@endforelse
				</div>
				<div class="chatbox-message-bottom">
					<form wire:submit.prevent="getAsk" x-ref="form" class="chatbox-message-form">
						<textarea 
							wire:model="ask" 
							wire:loading.attr="disabled" 
							wire:target="getAsk"
							x-on:keydown.enter="handleEnter($event)"
							id="chat-message-input"
							class="chatbox-message-input" 
							placeholder="{{ __('Type your message') }}" 
							rows="1"
							required
							autofocus>
						</textarea>
						@error('ask'){{ $message }}@enderror
						<button type="submit" class="chatbox-message-submit"><i class='fa-light fa-paper-plane'></i></button>
					</form>
				</div>
			</div>
		@endif
	</div>
</div>

@script
    <script>
        Alpine.data('chat', () => ({
			hasMore: $wire.entangle('hasMore'),
            lastScrollHeight: 0,

            init(){
                this.loadBubble();
				this.scrollListenerToTop();

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
            loadBubble(){
                // TOGGLE CHATBOX
                const chatboxToggle = document.querySelector('.chatbox-toggle')
                const chatboxMessage = document.querySelector('.chatbox-message-wrapper')
				if (!chatboxToggle || !chatboxMessage) return;
                chatboxToggle.addEventListener('click', function () {
                    chatboxMessage.classList.toggle('show')
                });
                // DROPDOWN TOGGLE
                const dropdownToggle = document.querySelector('.chatbox-message-dropdown-toggle')
                const dropdownMenu = document.querySelector('.chatbox-message-dropdown-menu')
                dropdownToggle.addEventListener('click', function () {
                    dropdownMenu.classList.toggle('show')
                });
            },
			scrollListenerToTop() {
                const container = document.getElementById('chatbox-message-content');
				if (!container) return;
                container.addEventListener('scroll', () => {
                    if (container.scrollTop === 0) {
						console.log('Scroll listener to top triggered');
                        this.loadMoreHistory();
                    }
                });
            },
            loadMoreHistory() {
                if(!this.hasMore) return;
                const container = document.getElementById('chatbox-message-content');
                this.lastScrollHeight = container.scrollHeight;
                // console.log('loadMoreHistory: lastScrollHeight =' + this.lastScrollHeight);
                @this.call('loadMore');
            },
            scrollToBottom() {
                // this.$nextTick(() => {
                    // console.log('scrollToBottom');
                    const messagesContainer = document.getElementById('chatbox-message-content');
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
                const container = document.getElementById('chatbox-message-content');
				if (!container) return;
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
