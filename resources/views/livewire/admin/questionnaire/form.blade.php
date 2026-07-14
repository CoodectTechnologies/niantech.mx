<div>
    @include('admin.components.errors')
    <form wire:submit.prevent="{{ $method }}" class="form d-flex flex-column flex-lg-row">
        <!--begin::Aside column-->
        <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
            <!--begin::Thumbnail settings-->
            <div class="card card-flush py-4">
                <!--begin::Card header-->
                <div class="card-header">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h2>{{ __('Image') }}</h2>
                    </div>
                    <!--end::Card title-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body text-center pt-0">
                    <div x-data="{ isUploading: false, progress: 0 }" x-on:livewire-upload-start="isUploading = true"
                        x-on:livewire-upload-finish="isUploading = false" x-on:livewire-upload-error="isUploading = false"
                        x-on:livewire-upload-progress="progress = $event.detail.progress">
                        @if($questionnaire->image)
                            <!--begin::Image input-->
                            <div class="image-input image-input-empty image-input-outline mb-3"
                                style="background-image: url({{ $questionnaire->imagePreview() }})">
                                <!--begin::Preview existing image-->
                                <div class="image-input-wrapper w-150px h-150px"></div>
                                <!--end::Preview existing image-->
                                <!--begin::Label-->
                                <label
                                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                    data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change image">
                                    <i class="fa-utility fa-semibold fa-pen-to-square fs-7"></i>
                                    <!--begin::Inputs-->
                                    <input wire:model="imageTmp" type="file" name="image" accept=".png, .jpg, .jpeg" />
                                    <!--end::Inputs-->
                                </label>
                                <!--end::Label-->
                                <!--begin::Remove-->
                                <span wire:click="removeImage"
                                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                    data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove image"
                                    wire:loading.attr="disabled" wire:target="removeImage"
                                    style="cursor: pointer;">
                                    <i class="bi bi-x fs-2" wire:loading.remove wire:target="removeImage"></i>
                                    <span wire:loading wire:target="removeImage" class="spinner-border spinner-border-sm"></span>
                                </span>
                                <!--end::Remove-->
                            </div>
                            <!--end::Image input-->
                        @elseif ($imageTmp)
                            <!--begin::Image input-->
                            <div class="image-input image-input-empty image-input-outline mb-3"
                                style="background-image: url({{ $imageTmp->temporaryUrl() }})">
                                <!--begin::Preview existing image-->
                                <div class="image-input-wrapper w-150px h-150px"></div>
                                <!--end::Preview existing image-->
                                <!--begin::Remove-->
                                <span wire:click="$set('imageTmp', null)"
                                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                    data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove image">
                                    <i class="bi bi-x fs-2"></i>
                                </span>
                                <!--end::Remove-->
                            </div>
                            <!--end::Image input-->
                        @else
                            <!--begin::Image input-->
                            <div class="image-input image-input-empty image-input-outline mb-3"
                                style="background-image: url({{ asset('assets/admin/media/svg/files/blank-image.svg') }})">
                                <!--begin::Preview existing image-->
                                <div class="image-input-wrapper w-150px h-150px"></div>
                                <!--end::Preview existing image-->
                                <!--begin::Label-->
                                <label
                                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                    data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change image">
                                    <i class="fa-utility fa-semibold fa-pen-to-square fs-7"></i>
                                    <!--begin::Inputs-->
                                    <input wire:model="imageTmp" type="file" name="image" accept=".png, .jpg, .jpeg" />
                                    <!--end::Inputs-->
                                </label>
                                <!--end::Label-->
                            </div>
                            <!--end::Image input-->
                        @endif
                        <!-- Progress -->
                        <div x-show="isUploading" class="progress mb-3">
                            <div class="progress-bar" role="progressbar" :style="`width: ${progress}%`"
                                :aria-valuenow="progress" aria-valuemin="0" aria-valuemax="100">
                                <span x-text="`${progress}%`"></span>
                            </div>
                        </div>
                    </div>
                    <!--begin::Description-->
                    <div class="text-muted fs-7">
                        {{ __('Set the main image. Only *.png, *.jpg, *.jpeg, *gif image files are accepted') }}</div>
                    <!--end::Description-->
                    @error('imageTmp')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                    @enderror
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Thumbnail settings-->
            <!--begin::Status-->
            <div class="card card-flush py-4">
                <!--begin::Card header-->
                <div class="card-header">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h2>{{ __('Status') }}</h2>
                    </div>
                    <!--end::Card title-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Select-->
                    <select required wire:model="questionnaire.status" class="form-select mb-2">
                        <option value="">{{ __('Select an option') }}</option>
                        <option value="Publicado">{{ __('Published') }}</option>
                        <option value="Borrador">{{ __('Draft') }}</option>
                    </select>
                    <!--end::Select-->
                    @error('questionnaire.status')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                    @enderror
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Status-->
            <!--begin::Min percentage-->
            <div class="card card-flush py-4">
                <!--begin::Card header-->
                <div class="card-header">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h2>{{ __('Minimum percentage') }}</h2>
                    </div>
                    <!--end::Card title-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Input-->
                    <input wire:model="questionnaire.min_positive_percentage" type="number" min="0" max="100"
                        class="form-control mb-2" placeholder="70" />
                    <!--end::Input-->
                    <div class="text-muted fs-7">
                        {{ __('Minimum percentage of positive answers to be apt') }}
                    </div>
                    @error('questionnaire.min_positive_percentage')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                    @enderror
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Min percentage-->
        </div>
        <!--end::Aside column-->
        <!--begin::Main column-->
        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
            <!--begin::General options-->
            <div class="card card-flush py-4">
                <!--begin::Card header-->
                <div class="card-header">
                    <div class="card-title">
                        <h2>{{ __('General') }}</h2>
                    </div>
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Input group-->
                    <div class="mb-10 fv-row">
                        <!--begin::Label-->
                        <label class="required form-label">{{ __('Name') }}</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input wire:model="translations.name.{{ translatable() }}" type="text" name="name"
                            class="form-control mb-2" placeholder="{{ __('Name') }}" />
                        <!--end::Input-->
                        @error('translations.name.'.translatable())
                            <small class="form-text text-danger" role="alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div>
                        <!--begin::Label-->
                        <label class="form-label">{{ __('Description') }}</label>
                        <!--end::Label-->
                        <!--begin::Editor-->
                        <textarea wire:model="translations.description.{{ translatable() }}" name="description"
                            class="form-control mb-2" rows="4" placeholder="{{ __('Description') }}"></textarea>
                        <!--end::Editor-->
                        @error('translations.description.'.translatable())
                            <small class="form-text text-danger" role="alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <!--end::Input group-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::General options-->
            <!--begin::Questions-->
            <div class="card card-flush py-4">
                <!--begin::Card header-->
                <div class="card-header">
                    <div class="card-title">
                        <h2>{{ __('Questions') }}</h2>
                    </div>
                    <div class="card-toolbar">
                        <button type="button" wire:click="addQuestion" class="btn btn-sm btn-light-primary">
                            <i class="fa fa-plus"></i> {{ __('Add question') }}
                        </button>
                    </div>
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    @foreach($questions as $index => $question)
                        <div class="border border-dashed border-gray-300 rounded p-5 mb-5" 
                            wire:key="question-{{ $index }}">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="mb-0">{{ __('Question') }} #{{ $index + 1 }}</h4>
                                <button type="button" wire:click="removeQuestion({{ $index }})" 
                                    class="btn btn-sm btn-light-danger"
                                    wire:loading.attr="disabled" wire:target="removeQuestion({{ $index }})">
                                    <i class="fa fa-trash" wire:loading.remove wire:target="removeQuestion({{ $index }})"></i>
                                    <span wire:loading wire:target="removeQuestion({{ $index }})" class="spinner-border spinner-border-sm"></span>
                                </button>
                            </div>
                            
                            <!--begin::Question text-->
                            <div class="mb-3">
                                <label class="form-label required">{{ __('Question text') }}</label>
                                <input wire:model="questions.{{ $index }}.question" type="text" 
                                    class="form-control" placeholder="{{ __('Question text') }}" />
                                @error('questions.'.$index.'.question')
                                    <small class="form-text text-danger" role="alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <!--end::Question text-->
                            
                            <!--begin::Question type-->
                            <div class="mb-3">
                                <label class="form-label required">{{ __('Question type') }}</label>
                                <select wire:model="questions.{{ $index }}.type" class="form-select">
                                    <option value="single">{{ __('Single selection (radio)') }}</option>
                                    <option value="multiple">{{ __('Multiple selection (checkbox)') }}</option>
                                </select>
                                @error('questions.'.$index.'.type')
                                    <small class="form-text text-danger" role="alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <!--end::Question type-->
                            
                            <!--begin::Options-->
                            <div class="bg-light p-4 rounded">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <label class="form-label mb-0">{{ __('Options') }}</label>
                                    <button type="button" wire:click="addOption({{ $index }})" 
                                        class="btn btn-sm btn-light-primary">
                                        <i class="fa fa-plus"></i> {{ __('Add option') }}
                                    </button>
                                </div>
                                
                                @foreach($question['options'] as $optionIndex => $option)
                                    <div class="d-flex gap-2 mb-2 align-items-start" 
                                        wire:key="option-{{ $index }}-{{ $optionIndex }}">
                                        <div class="flex-grow-1">
                                            <input wire:model="questions.{{ $index }}.options.{{ $optionIndex }}.text" 
                                                type="text" class="form-control form-control-sm" 
                                                placeholder="{{ __('Option text') }}" />
                                            @error('questions.'.$index.'.options.'.$optionIndex.'.text')
                                                <small class="form-text text-danger" role="alert">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input wire:model="questions.{{ $index }}.options.{{ $optionIndex }}.is_positive" 
                                                type="checkbox" class="form-check-input" 
                                                value="1"
                                                id="positive-{{ $index }}-{{ $optionIndex }}" />
                                            <label class="form-check-label" for="positive-{{ $index }}-{{ $optionIndex }}">
                                                {{ __('Positive') }}
                                            </label>
                                        </div>
                                        <button type="button" wire:click="removeOption({{ $index }}, {{ $optionIndex }})" 
                                            class="btn btn-sm btn-light-danger"
                                            wire:loading.attr="disabled" wire:target="removeOption({{ $index }}, {{ $optionIndex }})">
                                            <i class="fa fa-times" wire:loading.remove wire:target="removeOption({{ $index }}, {{ $optionIndex }})"></i>
                                            <span wire:loading wire:target="removeOption({{ $index }}, {{ $optionIndex }})" class="spinner-border spinner-border-sm"></span>
                                        </button>
                                    </div>
                                @endforeach
                                
                                @if(count($question['options']) === 0)
                                    <div class="alert alert-warning mb-0">
                                        {{ __('Add at least one option') }}
                                    </div>
                                @endif
                                
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="fa fa-info-circle"></i> 
                                        {{ __('Mark options as "Positive" that should count toward aptitude percentage') }}
                                    </small>
                                </div>
                            </div>
                            <!--end::Options-->
                        </div>
                    @endforeach
                    @if(count($questions) === 0)
                        <div class="alert alert-info">
                            {{ __('No questions added yet. Click "Add question" to start.') }}
                        </div>
                    @endif
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Questions-->
            <!--begin::Meta options-->
            <div class="card card-flush py-4">
                <!--begin::Card header-->
                <div class="card-header">
                    <div class="card-title">
                        <h2>{{ __('Meta Options') }}</h2>
                    </div>
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Input group-->
                    <div class="mb-10">
                        <!--begin::Label-->
                        <label class="form-label">{{ __('Meta Title') }}</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input wire:model="translations.meta_title.{{ translatable() }}" type="text" name="meta_title"
                            class="form-control mb-2" placeholder="{{ __('Meta Title') }}" />
                        <!--end::Input-->
                        @error('translations.meta_title.'.translatable())
                            <small class="form-text text-danger" role="alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="mb-10">
                        <!--begin::Label-->
                        <label class="form-label">{{ __('Meta Description') }}</label>
                        <!--end::Label-->
                        <!--begin::Textarea-->
                        <textarea wire:model="translations.meta_description.{{ translatable() }}" name="meta_description"
                            class="form-control mb-2" rows="3" placeholder="{{ __('Meta Description') }}"></textarea>
                        <!--end::Textarea-->
                        @error('translations.meta_description.'.translatable())
                            <small class="form-text text-danger" role="alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="mb-10">
                        <!--begin::Label-->
                        <label class="form-label">{{ __('Meta Keywords') }}</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input wire:model="translations.meta_keywords.{{ translatable() }}" type="text"
                            name="meta_keywords" class="form-control mb-2" placeholder="{{ __('Meta Keywords') }}" />
                        <!--end::Input-->
                        @error('translations.meta_keywords.'.translatable())
                            <small class="form-text text-danger" role="alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <!--end::Input group-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Meta options-->
            <div class="d-flex justify-content-end">
                <!--begin::Button-->
                <a href="{{ route('admin.questionnaire.index') }}" class="btn btn-light me-5">{{ __('Cancel') }}</a>
                <!--end::Button-->
                <!--begin::Button-->
                <button wire:loading.attr="disabled" wire:target="{{ $method }}" type="submit" class="btn btn-primary">
                    <span>{{ __('Save') }}</span>
                    <span wire:loading wire:target="{{ $method }}" class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </button>
                <!--end::Button-->
            </div>
        </div>
        <!--end::Main column-->
    </form>
</div>