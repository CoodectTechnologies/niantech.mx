<div class="flex-lg-row-fluid ms-lg-7 ms-xl-10">
    <div class="row justify-content-center">
        @can('proveedor erp')
            <div class="col-lg-6 col-sm-12 col-12 mb-5">
                @include('admin.setting.integration.erp.index')
            </div>
        @endcan
        @can('proveedor vadeto brands')
            <div class="col-lg-6 col-sm-12 col-12 mb-5">
                @include('admin.setting.integration.brands.index')
            </div>
        @endcan
    </div>
    @push('footer')
        <script>
            Livewire.on('render', function() {
                $('.modal').modal('hide');
            });
        </script>
    @endpush
</div>
