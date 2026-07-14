<script>
    if(window.Echo){
        window.Echo.private("App.Models.User.{{ auth()->id() }}")
            .notification((notification) => {
                Push.create(notification.title, {
                    body: notification.body,
                    icon: '{{ asset(config('app.logo')) }}',
                    timeout: 0,
                    onClick: () => {
                        window.focus();
                        this.close();
                        window.location = notification.url;
                    }
                });
            });
    }
</script>
