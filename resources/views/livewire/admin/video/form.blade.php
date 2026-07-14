<div>
    @include('admin.components.errors')
    <div x-data="{ platform: '{{ $video->platform }}' }">
        <form wire:submit.prevent="{{ $method }}">
                <div class="fv-row mb-7">
                    <label class="form-label required">{{ __('Platform') }}</label>
                    <select 
                        wire:model="video.platform"
                        x-model="platform"
                        class="form-select mb-2 @error('video.platform') invalid-feedback @enderror">
                        <option value="">{{ __('Select a option') }}</option>
                        <option value="YouTube">YouTube</option>
                        <option value="Vimeo">Vimeo</option>
                        <option value="Local">Local</option>
                    </select>
                    @error('video.platform')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                    @enderror
                </div>
                <div x-show="platform === 'Local'" x-cloak>
                    <div class="fv-row mb-7">
                        <label class="form-label required">{{ __('Video') }}</label>
                        <div x-data="{ isUploading: false, progress: 0 }" x-on:livewire-upload-start="isUploading = true"
                            x-on:livewire-upload-finish="isUploading = false" x-on:livewire-upload-error="isUploading = false"
                            x-on:livewire-upload-progress="progress = $event.detail.progress">
                            <div class="mt-1">
                                <div class="image-input image-input-outline">
                                    @if($videoTmp)
                                        <video class="img-fluid" src="{{ $videoTmp->temporaryUrl() }}" controls></video>
                                    @else
                                        <video class="img-fluid" src="{{ $video->videoPreview() }}" controls></video>
                                    @endif
                                    <label
                                        class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow image-input"
                                        data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Cambiar video">
                                        <i class="fa-utility fa-semibold fa-pen-to-square fs-7"></i>
                                        <input wire:model="videoTmp" class="d-none" type="file" name=""
                                            accept=".mp4, .mov, .wmv, .avi, .mkv, .webm" />
                                    </label>
                                    @if($videoTmp || $video->video)
                                        <span wire:click.prevent="removeVideo()"
                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                            data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="">
                                            <i class="fa-light fa-circle-trash fs-2"></i>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @error('videoTmp')
                                <small class="form-text text-danger" role="alert">{{ $message }}</small>
                            @enderror
                            <div x-show="isUploading" class="progress h-6px w-100">
                                <div class="progress-bar bg-primary" role="progressbar" :style="`width: ${progress}%;`"
                                    :aria-valuenow="`${progress}`" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="text-muted fs-7">*.mp4, *.mov, *.wmv, *avi, *mkv, *webm</div>
                    </div>
                </div>
                <div x-show="platform === 'YouTube' || platform === 'Vimeo'">
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-bold form-label mb-2"><span class="required">Iframe</span></label>
                        <textarea 
                            wire:model="video.iframe"
                            class="form-control mb-2 @error('video.iframe') invalid-feedback @enderror"
                            placeholder="Ingrese la iframe de la lección">{{ $video->iframe }}</textarea>
                        @error('video.iframe')
                            <small class="form-text text-danger" role="alert">{{ $message }}</small>
                        @enderror <br>
                        <code class="">{{ __('The iframe must be the one shared by the platform.') }}.</code>
                    </div>
                </div>
                <div class="d-flex justify-content-end py-5">
                    <button wire:loading.attr="disabled" wire:target="{{ $method }}" type="submit"
                        class="btn btn-primary">
                        <span class="indicator-label">{{ __('Save changes') }}</span>
                        <span wire:loading wire:target="{{ $method }}"
                            class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </button>
                </div>
            </form>
    </div>
   
</div>
