<?php

namespace App\Livewire\Admin\Setting\General;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads;

    public $logo;
    public $logoTmp;
    public $logoWhite;
    public $logoWhiteTmp;
    public $logoFavicon;
    public $logoFaviconTmp;
    public $name;
    public $colorPrimary;
    public $colorSecondary;
    public $method;
    public $destinationPath = 'assets/admin/media/logo/';

    protected function rules() {
        return [
            'logoTmp' => $this->logo ? 'image|nullable' : 'image|required',
            'logoWhiteTmp' => $this->logoWhite ? 'image|nullable' : 'image|required',
            'logoFaviconTmp' => $this->logoFavicon ? 'image|nullable' : 'image|required',
            'name' => $this->name ? 'nullable' : 'required',
            'colorPrimary' => $this->colorPrimary ? 'nullable' : 'required',
            'colorSecondary' => $this->colorSecondary ? 'nullable' : 'required',
        ];
    }
    public function mount($method) {
        $this->method = $method;
        $this->logo = config('app.logo');
        $this->logoWhite = config('app.logo_white');
        $this->logoFavicon = config('app.logo_favicon');
        $this->name = config('app.name');
        $this->colorPrimary = config('app.color_primary');
        $this->colorSecondary = config('app.color_secondary');
    }
    public function render() {
        return view('livewire.admin.setting.general.form');
    }
    public function update() {
        $this->validate();
        $this->saveLogo();
        $this->saveLogoWhite();
        $this->saveLogoFavicon();
        $this->saveName();
        $this->saveColors();
        if (file_exists(App::getCachedConfigPath())) {
            Artisan::call('config:cache');
        }
        $this->dispatch('alert', 'success', __('Registration successfully updated'));
        $this->dispatch('render');
    }
    public function logoPreview() {
        $url = asset('assets/admin/media/svg/files/blank-image.svg');
        if ($this->logo) {
            return asset($this->logo);
        } else {
            return $url;
        }
    }
    public function logoWhitePreview() {
        $url = asset('assets/admin/media/svg/files/blank-image.svg');
        if ($this->logoWhite) {
            return asset($this->logoWhite);
        } else {
            return $url;
        }
    }
    public function logoFaviconPreview() {
        $url = asset('assets/admin/media/svg/files/blank-image.svg');
        if ($this->logoFavicon) {
            return asset($this->logoFavicon);
        } else {
            return $url;
        }
    }
    private function saveLogo() {
        if ($this->logoTmp) {
            $imageName = 'logo.webp';
            $url = $this->logoTmp->store('directory-tmp');
            $this->imageManager($url, $imageName, 300, 'APP_LOGO');
        }
    }
    private function saveLogoWhite() {
        if ($this->logoWhiteTmp) {
            $imageName = 'logo_white.webp';
            $url = $this->logoWhiteTmp->store('directory-tmp');
            $this->imageManager($url, $imageName, 300, 'APP_LOGO_WHITE');
        }
    }
    private function saveLogoFavicon() {
        if ($this->logoFaviconTmp) {
            $imageName = 'logo_favicon.webp';
            $url = $this->logoFaviconTmp->store('directory-tmp');
            $this->imageManager($url, $imageName, 100, 'APP_LOGO_FAVICON');
        }
    }
    private function saveName() {
        if ($this->name) {
            setEnvValue('APP_NAME', $this->name);
        }
    }
    private function saveColors() {
        if ($this->colorPrimary) {
            setEnvValue('APP_COLOR_PRIMARY', $this->colorPrimary);
        }
        if ($this->colorSecondary) {
            setEnvValue('APP_COLOR_SECONDARY', $this->colorSecondary);
        }
    }
    private function imageManager($url, $imageName, $width, $envKEY) {
        // Obtener la imagen, optimizar y codificar
        $imageOptimized = ImageManagerStatic::make(Storage::get($url))
            ->widen($width)
            ->encode('webp');

        // Definir la ruta final (relativa a public/)
        $finalPath = $this->destinationPath.$imageName;

        // Guardar la imagen optimizada directamente en public/
        $fullPublicPath = public_path($finalPath);
        // Asegurar que el directorio existe
        if (! file_exists(dirname($fullPublicPath))) {
            mkdir(dirname($fullPublicPath), 0755, true);
        }
        file_put_contents($fullPublicPath, (string) $imageOptimized);

        // Eliminar el archivo temporal si existe
        if (Storage::exists($url)) {
            Storage::delete($url);
        }

        // Guardar la ruta en la variable de entorno
        setEnvValue($envKEY, $finalPath);
    }
}
