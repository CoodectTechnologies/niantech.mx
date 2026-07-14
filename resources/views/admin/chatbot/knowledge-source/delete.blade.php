<div x-data="knowledgeSource">
    <button x-on:click="confirmDestroy('{{ $source->id }}')" type="button" wire:loading.attr="disabled" wire:target="destroy('{{ $source->id }}')" class="btn btn-icon btn-sm btn-light-danger">
        <span wire:loading.remove wire:target="destroy('{{ $source->id }}')">
            <i class="fa-light fa-trash"></i>
        </span>
        <span wire:loading wire:target="destroy('{{ $source->id }}')" class="spinner-border spinner-border-sm"></span>
    </button>
</div>

@script
    <script>
        Alpine.data('knowledgeSource', () => ({
            confirmDestroy(id) {
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
                        $wire.destroy(id);
                    }
                });
            }
        }));
    </script>
@endscript
