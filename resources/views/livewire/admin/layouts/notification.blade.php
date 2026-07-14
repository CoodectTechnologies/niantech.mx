<div class="ms-2">
    <a href="{{ route('admin.notification.index') }}">
        <div wire:poll.60s.visible
            class="btn btn-icon btn-circle btn-light btn-color-gray-900 btn-active-color-primary w-40px h-40px position-relative"
            data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent"
            data-kt-menu-placement="bottom-end" id="kt_activities_toggle">
            <i class="ki-outline ki-notification-on fs-2"></i>
            @if($notificationsCount)
                {{-- <span class="bullet bullet-dot bg-success h-6px w-6px position-absolute translate-middle top-0 start-50 animation-blink"></span> --}}
                <span
                    class="position-absolute top-0 start-100 translate-middle badge badge-circle badge-danger w-15px h-15px ms-n4 mt-3">{{ $notificationsCount }}</span>
            @endif
        </div>
    </a>
</div>
