<!--begin:::Tabs-->
<ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-bold mb-lg-n2 me-auto">
    <!--begin:::Tab item-->
    <li class="nav-item">
        <a class="nav-link text-active-primary pb-4 {{ !$submodule ? 'active' : '' }}" data-bs-toggle="tab"
            href="#kt_chatbot_general">{{ __('General') }}</a>
    </li>
    <!--end:::Tab item-->
    <!--begin:::Tab item-->
    <li class="nav-item">
        <a class="nav-link text-active-primary pb-4 {{ $submodule == 'source' ? 'show active' : '' }}" data-bs-toggle="tab"
            href="#kt_chatbot_knowledge_source">{{ __('Knowledge Source') }}</a>
    </li>
    <!--end:::Tab item-->
    <!--begin:::Tab item-->
    <li class="nav-item">
        <a class="nav-link text-active-primary pb-4 {{ $submodule == 'chat' ? 'show active' : '' }}" data-bs-toggle="tab"
            href="#kt_chatbot_chat">{{ __('Chat') }}</a>
    </li>
    <!--end:::Tab item-->
</ul>
<!--end:::Tabs-->
