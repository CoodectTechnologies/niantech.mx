<div>
    <div class="d-flex flex-column gap-7 gap-lg-10">
        <div class="d-flex flex-wrap flex-stack gap-5 gap-lg-10">
            @include('admin.chatbot.chatbot.partials.show._menu')
        </div>
        <!--begin::Tab content-->
        <div class="tab-content">
            <!--begin::Tab pane-->
            <div wire:ignore.self class="tab-pane fade {{ !$submodule ? 'show active' : '' }}" id="kt_chatbot_general" role="tab-panel">
                @include('admin.chatbot.chatbot.partials.show._general')
            </div>
            <!--end::Tab pane-->
            <!--begin::Tab pane-->
            <div wire:ignore.self class="tab-pane fade {{ $submodule == 'source' ? 'show active' : '' }}" id="kt_chatbot_knowledge_source" role="tab-panel">
                @include('admin.chatbot.chatbot.partials.show._knowledge_source')
            </div>
            <!--end::Tab pane-->
            <!--begin::Tab pane-->
            <div wire:ignore.self class="tab-pane fade {{ $submodule == 'chat' ? 'show active' : '' }}" id="kt_chatbot_chat" role="tab-panel">
                @include('admin.chatbot.chatbot.partials.show._chat')
            </div>
            <!--end::Tab pane-->
        </div>
        <!--end::Tab content-->
    </div>
</div>
