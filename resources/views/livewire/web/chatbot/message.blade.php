<div class="chatbox-message-item {{ $message['role'] === 'user' ? 'sent' : 'received' }}">
    <div x-data="chatMessage">
        <div >
            <div wire:loading wire:target="generateContent" class="loader"></div>
            <span class="chatbox-message-item-text">
                @if($message['stream'])
                    <span x-ref="streamingMessage" wire:stream="stream.{{ $this->getId() }}">{!! $message['content'] !!}</span>
                @else
                    <span style="white-space: pre-line;">{!! $message['content'] !!}</span>
                @endif
            </span>
            <span class="chatbox-message-item-time">{{ $message['date'] }}</span>
        </div>
    </div>
</div>

@script
    <script>
        Alpine.data('chatMessage', () => ({
            observer: null,

            init(){
                this.observeStreaming();
            },
            observeStreaming() {
                const el = this.$refs.streamingMessage
                if (!el) return
                this.observer = new MutationObserver(() => {
                    this.$dispatch('scroll-to-bottom')
                })
                this.observer.observe(el, {
                    childList: true,
                    characterData: true,
                    subtree: true,
                });
            },
        }));
    </script>
@endscript