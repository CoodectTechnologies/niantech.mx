<div>
    <!--begin::Card-->
    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                    <span class="svg-icon svg-icon-1 position-absolute ms-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none">
                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                                transform="rotate(45 17.0365 15.1223)" fill="gray" />
                            <path
                                d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                fill="gray" />
                        </svg>
                    </span>
                    <input wire:model.live.debounce.500ms="search" type="search"
                        class="form-control form-control-solid w-250px ps-14" placeholder="{{ __('Search...') }}" />
                </div>
                <!--end::Search-->
            </div>
            <!--begin::Card title-->
            <!--begin::Card toolbar-->
            <div class="card-toolbar flex-row-fluid justify-content-start gap-5">
                <div class="w-100 mw-150px">
                    <div class="fv-row">
                        <div class="form-check form-switch form-check-custom form-check-solid">
                            <input wire:model.live="onlyParentsFilter" class="form-check-input" type="checkbox"
                                value="" id="onlyParents" />
                            <label class="form-check-label" for="onlyParents">
                                <span class="">{{ __('Only parents') }}</span>
                                @if($onlyParentsFilter)
                                    {{ __('Enabled') }}
                                @else
                                    {{ __('Disabled') }}
                                @endif
                                <span wire:loading wire:target="onlyParentsFilter"
                                    class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="w-100 mw-150px">
                    <div class="d-flex justify-content-between align-items-center">
                        <!--begin::Select2-->
                        <select wire:model.live="includeInMenuFilter" class="form-select form-select-solid">
                            <option value="">{{ __('In menu') }}</option>
                            <option value="1">{{ __('Including') }}</option>
                            <option value="2">{{ __('Not including') }}</option>
                        </select>
                        <!--end::Select2-->
                        <span wire:loading wire:target="includeInMenuFilter"
                            class="spinner-border spinner-border-sm align-middle ms-3"></span>
                    </div>
                </div>
                <div class="w-100 mw-150px">
                    <div class="d-flex justify-content-between align-items-center">
                        <!--begin::Select2-->
                        <select wire:model.live="statusFilter" class="form-select form-select-solid">
                            <option value="">{{ __('Status') }}</option>
                            <option value="1">{{ __('Enabled') }}</option>
                            <option value="2">{{ __('Disabled') }}</option>
                        </select>
                        <!--end::Select2-->
                        <span wire:loading wire:target="statusFilter"
                            class="spinner-border spinner-border-sm align-middle ms-3"></span>
                    </div>
                </div>
                <div class="w-100 mw-150px">
                    <div class="d-flex justify-content-between align-items-center">
                        <!--begin::Select2-->
                        <select wire:model.live="categoryFhaterFilter" class="form-select form-select-solid">
                            <option value="">{{ __('All') }}</option>
                            @foreach($categoriesFather as $categoryFather)
                                <option value="{{ $categoryFather->id }}">{{ $categoryFather->name }}</option>
                            @endforeach
                        </select>
                        <!--end::Select2-->
                        <span wire:loading wire:target="categoryFhaterFilter"
                            class="spinner-border spinner-border-sm align-middle ms-3"></span>
                    </div>
                </div>
                <div class="dropdown">
                    <a class="btn btn-light-primary dropdown-toggle" type="button" id="dropdownMenuButton1"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <!--begin::Svg Icon | path: icons/duotune/general/gen019.svg-->
                        <span class="svg-icon svg-icon-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <path
                                    d="M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11ZM15 6.5C15 7.9 16.1 9 17.5 9C18.9 9 20 7.9 20 6.5C20 5.1 18.9 4 17.5 4C16.1 4 15 5.1 15 6.5Z"
                                    fill="gray" />
                                <path opacity="0.3"
                                    d="M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 20 22 17.5 22ZM4 17.5C4 18.9 5.1 20 6.5 20C7.9 20 9 18.9 9 17.5C9 16.1 7.9 15 6.5 15C5.1 15 4 16.1 4 17.5Z"
                                    fill="gray" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                        {{ __('Actions') }} <span wire:loading wire:target="exportProducts"
                            class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li><a href="{{ route('admin.catalog.category.create') }}"
                                class="dropdown-item">{{ __('New') }}</a></li>
                        <li><a href="{{ route('admin.catalog.category.sortlist') }}"
                                class="dropdown-item">{{ __('Categories ordering') }}</a></li>
                    </ul>
                </div>
                <!--begin::Button-->
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body py-4">
            <!--begin::Table-->
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5">
                    <!--begin::Table head-->
                    <thead>
                        <!--begin::Table row-->
                        <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                            <th class="min-w-125px">{{ __('Category') }}</th>
                            <th class="min-w-125px">{{ __('Parent category') }}</th>
                            <th class="min-w-125px">{{ __('Categories children') }}</th>
                            <th class="min-w-125px">{{ __('Products') }}</th>
                            <th class="min-w-125px">{{ __('Date') }}</th>
                            <th class="min-w-125px">{{ __('Actions') }}</th>
                        </tr>
                        <!--end::Table row-->
                    </thead>
                    <!--end::Table head-->
                    <!--begin::Table body-->
                    <tbody class="text-gray-600 fw-bold">
                        @foreach($categories as $category)
                            <!--begin::Table row-->
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <!--begin::Thumbnail-->
                                        <a class="symbol symbol-50px">
                                            <span class="symbol-label"
                                                style="background-image:url({{ $category->imagePreview() }});"></span>
                                        </a>
                                        <!--end::Thumbnail-->
                                        <div class="ms-5">
                                            <!--begin::Title-->
                                            <a
                                                class="text-gray-800 text-hover-primary fs-5 fw-bolder">{{ $category->name }}</a>
                                            <!--end::Title-->
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $category->parent ? $category->parent->name : 'N/A' }}</td>
                                <td>{{ count($category->allChildrens) }}</td>
                                <td>{{ count($category->products) }}</td>
                                <td>
                                    {{ $category->dateToString() }}
                                </td>
                                <!--begin::Action=-->
                                <td class="">
                                    <!--begin::Show-->
                                    <a href="{{ route('ecommerce.product.index', ['category' => $category->slug]) }}"
                                        class="btn btn-icon btn-active-light-success w-30px h-30px me-3">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <!--begin::Update-->
                                    <a href="{{ route('admin.catalog.category.edit', $category) }}"
                                        class="btn btn-icon btn-active-light-success w-30px h-30px me-3">
                                        <!--begin::Svg Icon | path: icons/duotune/general/gen019.svg-->
                                        <span class="svg-icon svg-icon-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <path
                                                    d="M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11ZM15 6.5C15 7.9 16.1 9 17.5 9C18.9 9 20 7.9 20 6.5C20 5.1 18.9 4 17.5 4C16.1 4 15 5.1 15 6.5Z"
                                                    fill="gray" />
                                                <path opacity="0.3"
                                                    d="M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 20 22 17.5 22ZM4 17.5C4 18.9 5.1 20 6.5 20C7.9 20 9 18.9 9 17.5C9 16.1 7.9 15 6.5 15C5.1 15 4 16.1 4 17.5Z"
                                                    fill="gray" />
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon-->
                                    </a>
                                    <!--end::Update-->
                                    @include('admin.catalog.category.delete')
                                </td>
                                <!--end::Action=-->
                            </tr>
                            <!--end::Table row-->
                        @endforeach
                    </tbody>
                    <!--end::Table body-->
                </table>
            </div>
            <!--end::Table-->
            {{ $categories->links() }}
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
    @push('footer')
        <script>
            Livewire.on('render', function() {
                $('.modal').modal('hide');
            });
        </script>
    @endpush
</div>
