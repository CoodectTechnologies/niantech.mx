<div class="p-4">

    <h2 class="text-lg font-bold mb-3">Chat</h2>

    @foreach($messages as $message)
        <livewire:admin.test.chat-message
            :key="$message['id']"
            :message="$message"
        />
    @endforeach

    <form wire:submit.prevent="getAsk" class="mt-4">
        <div>
            <input type="text" class="border p-2 w-full" wire:model="ask" placeholder="Escribe tu mensaje...">
            <button type="submit">Enviar mensaje</button>
        </div>
        @error('ask'){{ $message }}@enderror
    </form>

</div>
