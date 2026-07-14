<div>
    @if($message['role'] === 'user')
        <div class="mb-3 p-3 rounded bg-gray-100 max-w-[70%] ml-auto text-end">
            <strong>Tú:</strong> {{ $message['content'] }}
        </div>
    @else
        <div class="mb-3 p-3 rounded bg-green-100 max-w-[70%] text-start">
            <strong>AI:</strong>
            @if($message['stream'])
                <span wire:stream="stream.{{ $this->getId() }}">{{ $message['content'] }}</span>
            @else
                <span>{{ $message['content'] }}</span>
            @endif
        </div>
    @endif
</div>