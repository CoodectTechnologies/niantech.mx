<?php

namespace App\Http\Controllers\Install\General;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic;

class GeneralController extends Controller
{
    public function index() {
        return view('install.general.index');
    }
    public function store(Request $request) {
        $validated = $request->validate([
            'appName' => 'required',
            'appUrl' => 'required',
            'logo' => 'nullable',
            'logoWhite' => 'nullable',
            'logoFavicon' => 'nullable',
        ]);
        try {
            setEnvValue('APP_NAME', $validated['appName']);
            setEnvValue('APP_URL', $validated['appUrl']);
            if (isset($validated['logo'])) {
                $imageName = 'logo-'.rand(1, 100).'.webp';
                $imagen = $request->file('logo');
                $url = $imagen->store('directory-tmp');
                $this->logoStore($url, $imageName, 300, 'APP_LOGO');
            }
            if (isset($validated['logoWhite'])) {
                $imageName = 'logo_white-'.rand(1, 100).'.webp';
                $imagen = $request->file('logoWhite');
                $url = $imagen->store('directory-tmp');
                $this->logoStore($url, $imageName, 300, 'APP_LOGO_WHITE');
            }
            if (isset($validated['logoFavicon'])) {
                $imageName = 'logo_favicon-'.rand(1, 100).'.webp';
                $imagen = $request->file('logoFavicon');
                $url = $imagen->store('directory-tmp');
                $this->logoStore($url, $imageName, 300, 'APP_LOGO_FAVICON');
            }

            return Redirect::route('install.database.index');
        } catch (Exception $e) {
            report($e);
            Session::flash('alert', 'Ocurrio un error: '.$e->getMessage());
            Session::flash('alert-type', 'warning');

            return Redirect::route('install.general.index');
        }
    }
    private function logoStore($url, $imageName, $width, $envKEY) {
        $destinationPath = 'assets/admin/media/logo/';
        $imageOptimized = ImageManagerStatic::make(Storage::get($url))->widen($width)->encode('webp');
        $urlNew = $destinationPath.$imageName;
        Storage::put($url, (string) $imageOptimized);
        rename(public_path(Storage::url($url)), public_path($urlNew));
        setEnvValue($envKEY, $urlNew);
    }
}
