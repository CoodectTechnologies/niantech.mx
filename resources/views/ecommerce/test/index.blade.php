@extends('ecommerce.layouts.main')

@section('content')
    <div x-data="app">
        <button x-on:click="testAlert">TEST ALERT</button>
    </div>
@endsection

@push('footer')
    <script>
        Alpine.data('app', () => ({
            init(){
            },
            testAlert(){
                // alertToastr('warning', 'Notification', 'Your changes have been saved and synced successfully.');
                toast({
                    type: 'success',             // success, error, warning, info, loading
                    title: 'Notification',       // required
                    id: 'my-id',                 // optional — use for updates
                    message: 'Text block',      // optional — plain text message
                    avatar: '{{ asset("assets/web/images/backgrounds/services-two-bg.jpg") }}', // optional — avatar image URL
                    avatarSize: '60px',         // optional — avatar size (default: 18px)
                    details: [                   // optional — expandable rows
                        { label: 'Key', value: 'Value' },
                        { label: 'Key', value: 'Value' },
                    ],
                    footer: 'Footer text',      // optional
                    actions: [                   // optional — buttons in expanded body
                        { label: 'Click me', icon: 'check', event: 'my-event', data: {}, color: '#22c55e', confirm: true },
                        { label: 'Click me', icon: 'check', event: 'my-event', data: {}, color: '#22c55e', confirm: true },
                    ],
                    duration: 5000,              // optional — override config duration
                    persistent: false,           // optional — never auto-dismiss
                    color: '#8b5cf6',            // optional — override type color
                    progress: 0.5,              // optional — show progress bar (0 to 1)
                    icon: 'star',                // optional — override type icon (registered name)
                    vibrate: true,               // optional — vibrate on mobile (true or [ms] pattern)
                });
            }
        }));
    </script>
@endpush
