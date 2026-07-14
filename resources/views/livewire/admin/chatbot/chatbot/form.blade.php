<div>
    <div>
        @include('admin.components.errors')
        <div class="card">
            <div class="card-body">
                <form class="form" wire:submit.prevent="save">
                    <div 
                        x-data="{ isUploading: false, progress: 0 }"
                        x-on:livewire-upload-start="isUploading = true" x-on:livewire-upload-finish="isUploading = false"
                        x-on:livewire-upload-error="isUploading = false"
                        x-on:livewire-upload-progress="progress = $event.detail.progress">
                        <!--begin::Label-->
                        <label class="fs-6 fw-bold mb-2">
                            <span class="">{{ __('Image') }}</span>
                            <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                                title="Tipo de archivo permitido: png, jpg, jpeg. gif, .webp"></i>
                        </label>
                        <!--end::Label-->
                        <!--begin::Image input wrapper-->
                        <div class="mt-1">
                            <!--begin::Image input-->
                            <div class="image-input image-input-outline">
                                <!--begin::Preview existing avatar-->
                                <div class="image-input-wrapper w-200px h-125px"
                                    @if($imageTmp) style="background-image: url('{{ $imageTmp->temporaryUrl() }}')"
                                    @else
                                        style="background-image: url('{{ $chatbot->imagePreview() }}')" @endif>
                                </div>
                                <!--end::Preview existing avatar-->
                                <!--begin::Edit-->
                                <label
                                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow image-input"
                                    data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                    title="{{ __('Change image') }}">
                                    <i class="fa-utility fa-semibold fa-pen-to-square fs-7"></i>
                                    <!--begin::Inputs-->
                                    <input wire:model="imageTmp" class="d-none" type="file" name=""
                                        accept=".png, .jpg, .jpeg, .gif, .webp" />
                                    <!--end::Inputs-->
                                </label>
                                <!--end::Edit-->
                                @if($imageTmp || $chatbot->image)
                                    <!--begin::Remove-->
                                    <span wire:click.prevent="removeImage()"
                                        class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="">
                                        <i class="fa-light fa-circle-trash fs-2"></i>
                                    </span>
                                    <!--end::Remove-->
                                @endif
                            </div>
                            <!--end::Image input-->
                        </div>
                        @error('imageTmp')
                            <small class="form-text text-danger" role="alert">{{ $message }}</small>
                        @enderror
                        <!-- Progress Bar -->
                        <div x-show="isUploading" class="progress h-6px w-100">
                            <div class="progress-bar bg-primary w-100" role="progressbar" :style="`width: ${progress}%;`"
                                :aria-valuenow="`${progress}`" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>

                    <!--begin::Name-->
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-bold form-label mb-2">
                            <span class="required">{{ __('Name') }}</span>
                        </label>
                        <input type="text" required wire:model="chatbot.name"
                            class="form-control form-control-solid @error('chatbot.name') is-invalid @enderror"
                            placeholder="{{ __('Example') }}: {{ __('Customer Support Chatbot') }}" />
                        @error('chatbot.name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!--end::Name-->

                    <!--begin::Model-->
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-bold form-label mb-2">
                            <span class="required">{{ __('Model') }}</span>
                        </label>
                        <select required wire:model="chatbot.model"
                            class="form-select form-select-solid @error('chatbot.model') is-invalid @enderror">
                            <option value="">{{ __('Select a model') }}</option>
                            @foreach($models as $model)
                                <option value="{{ $model->value }}">{{ $model->name }}</option>
                            @endforeach
                        </select>
                        @error('chatbot.model')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!--end::Model-->

                    <!--begin::Temperature-->
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-bold form-label mb-2">
                            <span class="required">{{ __('Temperature') }}</span>
                            <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                                title="{{ __('Controls randomness: Lower values (0.0-0.5) for focused responses, higher (0.6-1.0) for creative ones') }}"></i>
                        </label>
                        <input type="number" required wire:model="chatbot.temperature" step="0.1" min="0"
                            max="2"
                            class="form-control form-control-solid @error('chatbot.temperature') is-invalid @enderror"
                            placeholder="0.5" />
                        <div class="form-text">{{ __('Recommended: 0.5 for balanced responses') }}</div>
                        @error('chatbot.temperature')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!--end::Temperature-->

                    <!--begin::System Prompt-->
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-bold form-label mb-2">
                            <span class="required">{{ __('System Prompt') }}</span>
                            <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                                title="{{ __('Instructions that define the chatbot behavior and personality') }}"></i>
                        </label>
                        <textarea required wire:model="chatbot.system_promt"
                            class="form-control form-control-solid @error('chatbot.system_promt') is-invalid @enderror" rows="8"
                            placeholder="{{ __('Example') }}: {{ __('You are a helpful customer support assistant. Be polite, professional, and concise in your responses.') }}"></textarea>
                        <div class="form-text">{{ __('Define how the chatbot should behave and respond to users') }}
                        </div>
                        @error('chatbot.system_promt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!--end::System Prompt-->

                    <!--begin::Status-->
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-bold form-label mb-2">
                            <span class="required">{{ __('Status') }}</span>
                        </label>
                        <select required wire:model="chatbot.status"
                            class="form-select form-select-solid @error('chatbot.status') is-invalid @enderror">
                            <option value="1">{{ __('Active') }}</option>
                            <option value="0">{{ __('Inactive') }}</option>
                        </select>
                        @error('chatbot.status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!--end::Status-->

                    <!--begin::Actions-->
                    <div class="text-end pt-15">
                        <a href="{{ route('admin.chatbot.index') }}" class="btn btn-light me-3">
                            <i class="fa-light fa-arrow-left"></i> {{ __('Cancel') }}
                        </a>
                        <button wire:loading.attr="disabled" wire:target="save" type="submit" class="btn btn-primary">
                            <span class="indicator-label">
                                <i class="fa-light fa-save"></i> {{ __('Save changes') }}
                            </span>
                            <span wire:loading wire:target="save"
                                class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </button>
                    </div>
                    <!--end::Actions-->
                </form>
            </div>
        </div>
    </div>
</div>
