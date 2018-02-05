<?php

namespace App\Http\Controllers;

class LocaleController extends ApiController
{
    /**
     * Return application strings.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $languages = [];
        foreach (config('app.supported_locales') as $locale) {
            $languages[$locale] = [];
        }

        foreach ($languages as $locale => $strings) {
            $files = glob(resource_path('lang/' . $locale . '/*.php'));

            foreach ($files as $file) {
                $name = basename($file, '.php');
                $languages[$locale][$name] = require($file);
            }
        }

        return $this->respond($languages);
    }
}
