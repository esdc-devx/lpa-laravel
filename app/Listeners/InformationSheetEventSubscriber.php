<?php

namespace App\Listeners;

use Symfony\Component\Finder\Finder;

class InformationSheetEventSubscriber
{
    /**
     * Resolve all information sheet classes stored under
     * app/Models/InformationSheet/Sheets
     *
     * @return Illuminate\Support\Collection
     */
    protected function resolveInformationSheets()
    {
        $finder = new Finder();
        $informationSheetsClassPath = str_forward_slash(app_path('Models/InformationSheet/Sheets/'));
        $files = $finder->in("$informationSheetsClassPath/*")->files();

        // Iterate through all information sheet classes found and instanciate them.
        return collect($files)->transform(function($file) use ($informationSheetsClassPath) {
            $realPath = str_forward_slash($file->getRealPath());
            $relativePath = str_after($realPath, $informationSheetsClassPath);
            $classFile = str_backslash("App/Models/InformationSheet/Sheets/$relativePath");
            $class = str_before($classFile, '.php');
            return new $class;
        })->values();
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        // Dynamically resolve all information sheet classes and call their subscribe method
        // so that they can respond to any events fired by the application.
        $this->resolveInformationSheets()->each->subscribe($events);
    }
}
