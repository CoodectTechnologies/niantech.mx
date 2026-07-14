<button onclick="event.preventDefault(); confirmDestroyUser('{{ $user->slug }}')"
    class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm me-1">
    <i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span
            class="path3"></span><span class="path4"></span><span class="path5"></span></i>
</button>

@once
    @push('footer')
        <script>
            function confirmDestroyUser(slug) {
                swal.fire({
                    title: "{{ __('Are you sure?') }}",
                    text: "{{ __('You will not be able to retrieve this record') }}",
                    icon: "warning",
                    buttonsStyling: false,
                    showCancelButton: true,
                    confirmButtonText: "<i class='fa fa-trash'></i> <span class='font-weight-bold'>{{ __('Yes, delete') }}</span>",
                    cancelButtonText: "<i class='fas fa-arrow-circle-left'></i>  <span class='text-dark font-weight-bold'>{{ __('No, cancel') }}</span>",
                    reverseButtons: true,
                    cancelButtonClass: "btn btn-light-secondary font-weight-bold",
                    confirmButtonClass: "btn btn-danger",
                }).then(function(result) {
                    if (result.isConfirmed) {
                        @this.call('destroy', slug);
                    }
                });
            }
        </script>
    @endpush
@endonce
