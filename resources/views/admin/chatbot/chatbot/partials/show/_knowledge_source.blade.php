<div class="card">
    <div class="card-body">
        @livewire('admin.chatbot.knowledge-source.index', ['chatbot' => $chatbot, 'lazy' => true])
        @livewire('admin.chatbot.knowledge-source.form', ['chatbot' => $chatbot, 'lazy' => true])
    </div> 
</div>