<div>
    <!--begin::Knowledge Sources-->
    <div class="separator my-10"></div>
    <h3 class="mb-7">{{ __('Knowledge Sources') }}</h3>

    <!--begin::Existing Sources-->
    <div class="mb-7" wire:poll.5s.keep-alive.visible>
        @foreach($knowledgeSources as $source)
            <div class="d-flex align-items-center bg-light p-4 rounded mb-3">
                <div class="me-3">
                    @if($source->status === 'pending')
                        <i class="fa-light fa-clock fs-1x text-warning"></i>
                    @elseif($source->status === 'processing')
                        <span class="spinner-border spinner-border-sm text-primary"></span>
                    @elseif($source->status === 'completed')
                        <i class="fa-light fa-circle-check fs-1x text-success"></i>
                    @elseif($source->status === 'failed')
                        <i class="fa-light fa-circle-xmark fs-1x text-danger"></i>
                    @else
                        <i class="fa-light fa-ellipsis fs-1x text-muted"></i>
                    @endif
                </div>
                <div class="flex-grow-1 me-3">
                    <div class="fw-bold">{{ $source->name }}</div>
                    <div class="text-muted small">
                        <span class="badge badge-light-{{ $source->status === 'completed' ? 'success' : ($source->status === 'failed' ? 'danger' : 'warning') }} me-2">
                            {{ strtoupper($source->status) }}
                        </span>
                        <span class="badge badge-light-{{ $source->type === 'file' ? 'primary' : 'info' }}">
                            {{ strtoupper($source->type) }}
                        </span>
                        <a href="{{ $source->pathToString() }}" target="_blank" rel="noopener noreferrer">{{ $source->pathToString() }}</a>
                    </div>
                    @if($source->status_message)
                        <div class="text-danger small mt-1">
                            {{ $source->status_message }}
                        </div>
                    @endif
                </div>
                <div class="d-flex align-items-center gap-2">
                    @if($source->status === 'failed')
                        <button type="button"
                                class="btn btn-icon btn-sm btn-light-info me-2"
                                wire:click="regenerate('{{ $source->id }}')"
                                wire:loading.attr="disabled"
                                wire:target="regenerate('{{ $source->id }}')">
                            <span wire:loading.remove wire:target="regenerate('{{ $source->id }}')">
                                <i class="fa-light fa-rotate-right"></i>
                            </span>
                            <span wire:loading wire:target="regenerate('{{ $source->id }}')" class="spinner-border spinner-border-sm"></span>
                        </button>
                    @endif
                    @include('admin.chatbot.knowledge-source.delete')
                </div>
            </div>
        @endforeach
    </div>
    <!--end::Existing Sources-->
</div>
