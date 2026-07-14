<style>
#theme-toggle{
    position: fixed;
    right: 0;
    bottom: 120px;

    width: 56px;
    height: 56px;

    border: 0;
    border-radius: 24px 0 0 24px;

    background: #343a40;
    color: #fff;

    display:flex;
    justify-content:center;
    align-items:center;

    cursor:pointer;
    z-index:1050;

    transition:.3s;
}

#theme-toggle:hover{
    width:64px;
}

#theme-toggle::before{
    content:"";
    position:absolute;
    top:-7px;
    right:0;
    width:14px;
    height:14px;
    background:
        radial-gradient(circle at bottom left,
            transparent 7px,
            #343a40 8px);
    transform:rotate(90deg);
}

#theme-toggle::after{
    content:"";
    position:absolute;
    bottom:-7px;
    right:0;
    width:14px;
    height:14px;
    background:
        radial-gradient(circle at top left,
            transparent 7px,
            #343a40 8px);
}

/* Dark */

[data-bs-theme="dark"] #theme-toggle{
    background:#fff;
    color:#212529;
}

[data-bs-theme="dark"] #theme-toggle::before{
    background:
        radial-gradient(circle at bottom left,
            transparent 7px,
            #fff 8px);
}

[data-bs-theme="dark"] #theme-toggle::after{
    background:
        radial-gradient(circle at top left,
            transparent 7px,
            #fff 8px);
}
</style>

<button
    id="theme-toggle"
    class="theme-toggle"
    type="button"
    aria-label="Cambiar tema">

    <i class="fas fa-sun theme-icon-light"></i>
    <i class="fas fa-moon theme-icon-dark d-none"></i>

</button>
<a href="https://wa.me/{{ config('contact.whatsapp') }}">
    <img class="btn-whatsapp" src="{{ asset('assets/ecommerce/images/contact/whatsapp.webp') }}" width="64" height="64" alt="WhatsApp">
</a>
<footer class="footer">
    @if(config('services.chatbot.status'))
        @livewire('web.chatbot.index', ['lazy' => true])
    @endif
    @livewire('ecommerce.newsletter.index')
    @livewire('ecommerce.layouts.footer')
</footer>