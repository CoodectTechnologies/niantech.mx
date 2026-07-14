<?php

use App\Models\Currency;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic;

if (! function_exists('active')) {
    function active($routeNames) {
        $class = '';
        if (is_array($routeNames)) {
            foreach ($routeNames as $routeName) {
                if (setActive($routeName)) {
                    $class = 'menu-item-active active menu-active hover show';
                    break;
                }
            }
        } else {
            if (setActive($routeNames)) {
                $class = 'menu-item-active active menu-active hover show';
            }
        }

        return $class;
    }
}
if (! function_exists('setActive')) {
    function setActive($routeName) {
        return request()->routeIs($routeName);
    }
}
if (! function_exists('formatBytes')) {
    function formatBytes($size, $precision = 2) {
        $base = log($size, 1024);
        $suffixes = ['', 'KB', 'MB', 'GB', 'TB'];

        return round(pow(1024, $base - floor($base)), $precision).' '.$suffixes[floor($base)];
    }
}
if (! function_exists('currency')) {
    function currency() {
        return session()->get('currency');
    }
}
if (! function_exists('currencySymbol')) {
    function currencySymbol($code = null) {
        $code = $code ?? currency();
        $currency = Currency::getCurrencyByCode($code);
        if ($currency) {
            return $currency->symbol;
        } else {
            return '$';
        }
    }
}
if (! function_exists('currencies')) {
    function currencies() {
        return Currency::getCache();
    }
}
if (! function_exists('language')) {
    function language() {
        return session()->get('language') ?? App::getLocale();
    }
}
if (! function_exists('languages')) {
    function languages() {
        return config('translatable.status') ? config('translatable.locales') : [];
    }
}
if (! function_exists('countryByLanguage')) {
    function countryByLanguage($language = null) {
        $language = $language ?? language();
        $country = [];
        foreach (config('translatable.countries') as $countryName => $countryInfo) {
            if ($countryInfo['language_code'] == $language) {
                $country = $countryInfo;
                break;
            }
        }

        return $country;
    }
}
if (! function_exists('translatable')) {
    function translatable() {
        return config('translatable.status') ? session()->get('language') : config('translatable.fallback');
    }
}
if (! function_exists('convertCurrencyBySession')) {
    function convertCurrencyBySession($price, $currencyModelCode, $currencyValue, $currencyDefault = true) {
        $currencySession = Currency::getCurrencyByCode(session()->get('currency')); // Objeto del modelo de la moneda según la moneda de la sesión
        // Verificar si la moneda del producto es igual a la moneda de la sesión
        if ($currencyModelCode === $currencySession->code) {
            return $price; // Si son iguales, retornar el precio sin necesidad de conversión
        }
        // Checamos si la moneda del modelo es la moneda default del sistema
        if ($currencyDefault) {
            // Si la moneda del producto es la moneda por defecto, convertir el precio de la sesión a la moneda del producto
            $convertedPrice = round($price / $currencySession->value, 0);
        } else {
            // Si la moneda del producto no es la moneda por defecto, primero convertir el precio de la moneda del producto a la moneda por defecto y luego a la moneda de la sesión
            $convertedPrice = round($price * $currencyValue / $currencySession->value, 0);
        }

        return $convertedPrice;
    }
}
if (! function_exists('imageManager')) {
    function imageManager($url, $width, $model, $name = null) {
        $extension = pathinfo($url, PATHINFO_EXTENSION);
        // Si la extensión no es 'gif' ni 'webp' y la imagen existe, procede con la optimización
        if (! in_array($extension, ['gif', 'webp', 'webm']) && Storage::exists($url)) {
            try {
                $imageOptimized = ImageManagerStatic::make(Storage::get($url))->widen($width)->encode('webp');
                $urlEncode = $url.'.webp';
                Storage::put($urlEncode, (string) $imageOptimized);
                Storage::delete($url);
                $url = $urlEncode;
            } catch (Exception $e) {
                report($e);
                // Si ocurre un error al optimizar la imagen, se elimina la imagen porque esta corrupta
                Storage::delete($url);

                return;
            }
        }
        // Actualiza o crea el modelo de imagen
        $imageData = ['url' => $url, 'main' => true, 'name' => $name];
        if (! $name) {
            $model->image()->exists() ? $model->image()->update($imageData) : $model->image()->create($imageData);
        } else {
            $model->$name()->exists() ? $model->$name()->update($imageData) : $model->$name()->create($imageData);
        }
    }
}
if (! function_exists('imagesManager')) {
    function imagesManager($url, $width, $model, $name = null) {
        $extension = pathinfo($url, PATHINFO_EXTENSION);
        // Si la extensión no es 'gif' ni 'webp' y la imagen existe, se procede con la optimización
        if (! in_array($extension, ['gif', 'webp', 'webm']) && Storage::exists($url)) {
            try {
                $imageOptimized = ImageManagerStatic::make(Storage::get($url))->widen($width)->encode('webp');
                $urlEncode = $url.'.webp';
                Storage::put($urlEncode, (string) $imageOptimized);
                Storage::delete($url);
                $url = $urlEncode;
            } catch (Exception $e) {
                report($e);
                // Si ocurre un error al optimizar la imagen, se elimina la imagen porque está corrupta
                Storage::delete($url);

                return;
            }
        }
        // Crea el modelo de imagen
        $model->images()->create(['url' => $url, 'name' => $name]);
    }
}
if (! function_exists('sectionMenuIsVisible')) {
    function sectionMenuIsVisible($section) {
        $response = false;
        foreach ($section['modules'] as $module) {
            if ($module['urlName']) {
                if (
                    Route::has($module['urlName']) &&
                    auth()->user()->canany($module['canany'])
                ) {
                    $response = true;
                }
            } else {
                foreach ($module['submodules'] as $submodule) {
                    if ($submodule['urlName']) {
                        if (
                            Route::has($submodule['urlName']) &&
                            auth()->user()->canany($submodule['canany'])
                        ) {
                            $response = true;
                        }
                    }
                }
            }
        }

        return $response;
    }
}
if (! function_exists('moduleMenuIsVisible')) {
    function moduleMenuIsVisible($module) {
        $response = false;
        if ($module['urlName']) {
            if (Route::has($module['urlName'])) {
                $response = true;
            }
        } else {
            foreach ($module['submodules'] as $submodule) {
                if ($submodule['urlName']) {
                    if (Route::has($submodule['urlName'])) {
                        $response = true;
                        break;
                    }
                }
            }
        }

        return $response;
    }
}
if (! function_exists('mediaManagerSeeder')) {
    function mediaManagerSeeder($url, $pathSave) {
        $publicPath = public_path($url);
        if (File::exists($publicPath)) {
            $fileName = basename($pathSave);
            $pathSave = str_replace($fileName, '', $pathSave);
            $pathSave = Storage::putFileAs($pathSave, $publicPath, $fileName);
            $pathSave = str_replace('//', '/', $pathSave);

            return $pathSave;
        } else {
            try {
                $fileContent = file_get_contents($url);

                return Storage::put($pathSave, $fileContent);
            } catch (Exception $e) {
                report($e);

                return null;
            }
        }
    }
}
if (! function_exists('setEnvValue')) {
    function setEnvValue($key, $value) {
        $path = base_path('.env');
        $lines = file($path, FILE_IGNORE_NEW_LINES);

        // Detecta tipo y prepara valor para escribir
        if (is_bool($value)) {
            $value = $value ? 'true' : 'false';
        } elseif (is_numeric($value)) {
            $value = $value;
        } else {
            // Primero escapar backslashes existentes para no duplicar los que añadimos
            $val = str_replace('\\', '\\\\', $value);
            // Reemplazamos saltos de línea por la secuencia literal \n (un solo backslash)
            $val = str_replace(["\r\n", "\r", "\n"], ['\\n', '\\n', '\\n'], $val);
            // Escapar comillas dobles
            $val = str_replace('"', '\\"', $val);
            $value = '"'.$val.'"';
        }

        $found = false;
        $keyPattern = '/^'.preg_quote($key, '/').'=/';
        for ($i = 0; $i < count($lines); $i++) {
            if (preg_match($keyPattern, $lines[$i])) {
                // Reemplaza la línea y elimina bloques siguientes que no sean otra VARIABLE=...
                $lines[$i] = "{$key}={$value}";
                $j = $i + 1;
                while (isset($lines[$j]) && ! preg_match('/^[A-Z0-9_]+=/', $lines[$j])) {
                    array_splice($lines, $j, 1);
                }
                $found = true;
                break;
            }
        }

        if (! $found) {
            $lines[] = "{$key}={$value}";
        }

        file_put_contents($path, implode("\n", $lines)."\n");
    }
}
if (! function_exists('arrayMergeDeep')) {
    function arrayMergeDeep(array $default, array $custom): array {
        $mapped = [];
        foreach ($default as $item) {
            $mapped[$item[0]] = $item;
        }
        foreach ($custom as $item) {
            $mapped[$item[0]] = $item;
        }

        return array_values($mapped);
    }
}
