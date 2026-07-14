<div>
    <div x-data="chatMessage">
        @if($message['role'] === 'user')
            <!-- User Message -->
            <div class="d-flex justify-content-end mb-10">
                <div class="d-flex flex-column align-items-end">
                    <div class="d-flex align-items-center mb-2">
                        <div class="me-3">
                            <span class="text-muted fs-7 mb-1">{{ $message['date'] }}</span>
                            <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary ms-1">{{ $user->name ?? __('Anonimous') }}</a>
                        </div>
                        <div class="symbol symbol-35px symbol-circle">
                            <div class="symbol-label fs-6 fw-bold text-primary bg-light-primary">
                                {{ Str::substr($user->name ?? __('Anonimous'), 0, 1) }}
                            </div>
                        </div>
                    </div>
                    <div class="p-5 rounded bg-light-primary text-gray-900 fw-semibold mw-lg-400px text-end" style="white-space: pre-line;">
                        {!! $message['content'] !!}
                    </div>
                </div>
            </div>
        @else
            <div wire:loading wire:target="generateContent" class="loader"></div>
            <!-- Assistant Message -->
            <div class="d-flex justify-content-start mb-10">
                <div class="d-flex flex-column align-items-start">
                    <div class="d-flex align-items-center mb-2">
                        <div class="symbol symbol-35px symbol-circle">
                            <div class="symbol-label fs-6 fw-bold text-info bg-light-info">
                                <i class="fa-light fa-robot"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary me-1">{{ $chatbot->name }}</a>
                            <span class="text-muted fs-7 mb-1">{{ $message['date'] }}</span>
                        </div>
                    </div>
                    <div class="p-5 rounded bg-light-info text-gray-900 fw-semibold mw-lg-400px text-start">
                        @if($message['stream'])
                            <span x-ref="streamingMessage" wire:stream="stream.{{ $this->getId() }}" style="white-space: pre-line;">{!! $message['content'] !!}</span>
                        @else
                            <span style="white-space: pre-line;">{!! $message['content'] !!}</span>
                        @endif
                    </div>
                </div>
            </div>
        @endif
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