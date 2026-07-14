<livewire:sileo-toaster position="bottom-center"/>

<script>
    window.addEventListener('load', () => {
        @if(session()->has('alert'))
            let type = '{{ session()->get('alert-type') }}';
            let alert = '{{ session()->get('alert') }}';
            let alertDescription = '{{ session()->get('alert-description') }}';
            toast(type, alert, alertDescription)
        @endif
        Livewire.on('alert', data => {
            let type = data[0];
            let alert = data[1];
            let alertDescription = data[2] ?? null;
            toast(type, alert, alertDescription)
        });
        function toast(type, alert, alertDescription = null){
            window.dispatchEvent(new CustomEvent('sileo', {
                detail: {
                    action: null,
                    description: alertDescription,
                    duration: 4000,
                    position: 'bottom-center',
                    title: alert,
                    type: type,
                }
            }))
        }
    });
</script>