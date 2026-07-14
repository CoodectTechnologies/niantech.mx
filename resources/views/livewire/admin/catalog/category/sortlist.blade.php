<div>
    <div x-data="{ open: {} }">
        <div class="form">
            <div class="row">
                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <h3>{{ __('General data') }}</h3>
                            </div>
                        </div>
                        <div wire:ignore class="card-body">
                            <ul id="category-tree" class="card">
                                @foreach($categories as $category)
                                    @include('admin.catalog.category.partials.sortlist._category', [
                                        'category' => $category,
                                    ])
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="text-end pt-15">
                        <a href="{{ route('admin.catalog.category.index') }}" class="btn btn-light me-3"><i
                                class="fa fa-arrow-left"></i></a>
                        <button onclick="sendData()" wire:loading.attr="disabled" wire:target="updateOrder"
                            type="button" class="btn btn-primary">
                            <span class="indicator-label">{{ __('Save changes') }}</span>
                            <span wire:loading wire:target="updateOrder"
                                class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @push('footer')
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
            <script>
                var sortlist = [];
                document.addEventListener('livewire:init', function() {
                    const setupSortable = (element) => {
                        new Sortable(element, {
                            group: 'nested',
                            animation: 150,
                            fallbackOnBody: true,
                            swapThreshold: 0.65,
                            onEnd: function(evt) {
                                const item = evt.item;
                                const newParentId = item.parentElement.closest('li') ? item.parentElement
                                    .closest('li').dataset.id : null;
                                const siblings = Array.from(item.parentElement.children);
                                const order = siblings.indexOf(item);
                                const siblingIds = siblings.map(sibling => sibling.dataset.id);
                                sortlist[item.dataset.id] = {
                                    id: item.dataset.id,
                                    parent_id: newParentId,
                                    order: order,
                                    sibling_ids: siblingIds
                                };
                                console.log(sortlist);
                            }
                        });
                    };
                    setupSortable(document.getElementById('category-tree'));
                    document.querySelectorAll('#category-tree ul').forEach((element) => {
                        setupSortable(element);
                    });
                });

                function sendData() {
                    @this.call('updateOrder', Object.values(sortlist));
                }
            </script>
        @endpush
    </div>
