
<div class="card mb-4">
    <div class="card-header d-flex align-items-center">
        <i class="fa fa-image fa-2x me-2"></i>
        <span>{{ __('Imagen principal') }}</span>
    </div>
    <div class="card-body text-center">
        <div class="rounded border border-primary p-2 bg-light mb-2" style="width: 150px; margin: 0 auto;">
            <img src="{{ $imageTmp ? $imageTmp->temporaryUrl() : $product->imagePreview() }}" class="img-fluid rounded" alt="Imagen principal">
        </div>
        <button type="button" class="btn btn-sm btn-primary mt-2">
            <label class="" for="imageTmp">
                <i class="fa fa-pen"></i> {{ __('Cambiar imagen') }}
            </label>
        </button>
        <div x-data="{ isUploading: false, progress: 0 }"
            x-on:livewire-upload-start="isUploading = true"
            x-on:livewire-upload-finish="isUploading = false"
            x-on:livewire-upload-error="isUploading = false"
            x-on:livewire-upload-progress="progress = $event.detail.progress">
            <input wire:model="imageTmp" id="imageTmp" class="d-none" type="file" name="" accept=".png, .jpg, .jpeg, .gif, .webp" />
            <div x-show="isUploading" class="progress h-6px w-100 mt-2">
                <div class="progress-bar bg-primary" role="progressbar" :style="`width: ${progress}%;`"
                    :aria-valuenow="`${progress}`" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
        @if($imageTmp || $product->image)
            <button class="btn btn-sm btn-danger mt-2" wire:click="removeImageMain">
                <i class="fa fa-trash"></i> {{ __('Eliminar') }}
            </button>
        @endif
        <small class="text-muted d-block mt-2">{{ __('Establece la imagen principal. Solo .jpg, .png, .webp') }}</small>
    </div>
</div>