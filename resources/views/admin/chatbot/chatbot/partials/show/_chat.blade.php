<div class="d-flex flex-column flex-lg-row">
    <div class="flex-column flex-lg-row-auto w-100 w-lg-300px w-xl-400px mb-10 mb-lg-0">
        @livewire('admin.chatbot.chat.index', ['chatbot' => $chatbot, 'lazy' => false])
    </div>
    <div class="flex-lg-row-fluid ms-lg-7 ms-xl-10">
        @livewire('admin.chatbot.chat.show', ['chatbot' => $chatbot, 'lazy' => false])
    </div>
</div> 