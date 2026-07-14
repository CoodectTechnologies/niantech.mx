<div>
<!--begin::Add New Source-->
    <div class="card">
        <div class="card-body bg-light">
            <h5 class="mb-5">{{ __('Add Knowledge Source') }}</h5>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">{{ __('Name') }}</label>
                    <input type="text" wire:model="newSource.name" class="form-control @error('newSource.name') is-invalid @enderror" placeholder="{{ __('Source name') }}" />
                    @error('newSource.name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">{{ __('Type') }}</label>
                    <select wire:model.live="newSource.type" class="form-select">
                        <option value="file">{{ __('File') }} (PDF)</option>
                        <option value="url">URL</option>
                    </select>
                </div>

                @if($newSource['type'] === 'file')
                    <div class="col-12">
                        <label class="form-label">{{ __('File') }}</label>
                        <div
                            x-data="{ uploading: false, progress: 0 }"
                            x-on:livewire-upload-start="uploading = true"
                            x-on:livewire-upload-finish="uploading = false"
                            x-on:livewire-upload-cancel="uploading = false"
                            x-on:livewire-upload-error="uploading = false"
                            x-on:livewire-upload-progress="progress = $event.detail.progress"
                        >
                            <input type="file" wire:model="newSource.file" class="form-control @error('newSource.file') is-invalid @enderror" accept=".pdf" />
                            <div x-show="uploading">
                                <progress max="100" x-bind:value="progress" class="w-100"></progress>
                            </div>
                        </div>
                        <div class="form-text">{{ __('Max 10MB. Formats: PDF') }}</div>
                        @error('newSource.file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                @else
                    <div class="col-12">
                        <label class="form-label">URL</label>
                        <input type="url" wire:model="newSource.path" class="form-control @error('newSource.path') is-invalid @enderror" placeholder="https://example.com/documentation" />
                        @error('newSource.path') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                @endif

                <div class="col-12 text-end">
                    <button type="button" wire:click="save" wire:loading.attr="disabled" class="btn btn-light-primary">
                        <span wire:loading.remove wire:target="save">
                            <i class="fa-light fa-save"></i> {{ __('Add Source') }}
                        </span>
                        <span wire:loading wire:target="save">
                            <span class="spinner-border spinner-border-sm me-2"></span>
                            {{ __('Adding...') }}
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!--end::Add New Source-->
</div>
