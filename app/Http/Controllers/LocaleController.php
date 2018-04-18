<?php

namespace App\Http\Controllers;

use \DirectoryIterator;
class LocaleController extends APIController
{
    /**
     * Return application strings.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $translations = [];
        // Build up the locales in the response.
        foreach (config('app.supported_locales') as $locale) {
            $translations[$locale] = [];

            $path = resource_path("lang/$locale");

            $data = $this->getDirectoryAsArray(new DirectoryIterator($path));
            $translations[$locale] = $data;
        }

        return $this->respond($translations);
    }

    protected function getDirectoryAsArray(DirectoryIterator $dir)
    {
        $data = [];
        foreach ($dir as $node) {
            if ($node->isDir() && !$node->isDot()) {
                $data[$node->getFilename()] = $this->getDirectoryAsArray(new DirectoryIterator($node->getPathname()));
            } elseif ($node->isFile()) {
                $name = basename($node, '.php');
                $data[$name] = require($node->getPathname());
            }
        }
        return $data;
    }
}
