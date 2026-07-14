
<div class="card mb-4">
    <div class="card-header d-flex align-items-center">
        <i class="fa fa-file-pdf fa-2x me-2"></i>
        <span>{{ __('Ficha técnica') }}</span>
    </div>
    <div class="card-body text-center">
        <div x-data="{ isUploading: false, progress: 0 }"
            x-on:livewire-upload-start="isUploading = true"
            x-on:livewire-upload-finish="isUploading = false"
            x-on:livewire-upload-error="isUploading = false"
            x-on:livewire-upload-progress="progress = $event.detail.progress">
            @if($technicalDatasheetTmp)
                <a href="{{ $technicalDatasheetTmp->temporaryUrl() }}" target="_blank" class="btn btn-sm btn-danger">
                    <i class="fa fa-file-pdf"></i> {{ __('Ver ficha técnica') }}
                </a>
                <button class="btn btn-sm btn-secondary mt-2" wire:click="removeTechnicalDatasheet">
                    <i class="fa fa-trash"></i> {{ __('Eliminar') }}
                </button>
            @elseif ($product->technical_datasheet && Storage::exists($product->technical_datasheet))
                <a href="{{ Storage::url($product->technical_datasheet) }}" target="_blank" class="btn btn-sm btn-danger">
                    <i class="fa fa-file-pdf"></i> {{ __('Ver ficha técnica') }}
                </a>
                <button class="btn btn-sm btn-secondary mt-2" wire:click="removeTechnicalDatasheet">
                    <i class="fa fa-trash"></i> {{ __('Eliminar') }}
                </button>
            @else
                <input wire:model="technicalDatasheetTmp" type="file" class="form-control mb-2" accept=".pdf">
                <small class="text-muted">{{ __('Sube un archivo PDF.') }}</small>
            @endif
            @error('technicalDatasheetTmp')
                <small class="form-text text-danger" role="alert">{{ $message }}</small>
            @enderror
            <div x-show="isUploading" class="progress h-6px w-100 mt-2">
                <div class="progress-bar bg-primary" role="progressbar" :style="`width: ${progress}%;`"
                    :aria-valuenow="`${progress}`" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
        <div class="text-muted fs-7 mt-2">{{ __('Establece una ficha técnica. Solo archivos PDF son aceptados.') }}</div>
    </div>
</div>
