<button
    onclick="event.preventDefault(); confirmDestroy({{ $planFeature->id }})"
    class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm me-1">
    <i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
</button>

@once
    @push('footer')
        <script>
            function confirmDestroy(id){
                swal.fire({
                    title: "{{ __('¿Estás seguro?') }}",
                    text: "{{ __('No podrá recuperar este registro') }}",
                    icon: "warning",
                    buttonsStyling: false,
                    showCancelButton: true,
                    confirmButtonText: "<i class='fa fa-trash'></i> <span class='font-weight-bold'>{{ __('Si, eliminar') }}</span>",
                    cancelButtonText: "<i class='fas fa-arrow-circle-left'></i>  <span class='text-dark font-weight-bold'>{{ __('No, cancelar') }}</span>",
                    reverseButtons: true,
                    cancelButtonClass: "btn btn-light-secondary font-weight-bold",
                    confirmButtonClass: "btn btn-danger"
                }).then(function(result) {
                    if (result.isConfirmed) {
                        @this.call('destroy', id);
                    }
                });
            }
        </script>
    @endpush
@endonce
