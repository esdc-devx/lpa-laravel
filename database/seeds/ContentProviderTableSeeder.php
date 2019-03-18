<?php

use App\Models\Lists\ContentProvider;
use Illuminate\Database\Seeder;

class ContentProviderTableSeeder extends Seeder
{
    protected function data()
    {
        return [
            ['name_en' => 'Canada School of Public Service', 'name_fr' => 'École de la fonction publique du Canada'],
            ['name_en' => 'Government of Canada - Other Department ', 'name_fr' => 'Autre ministère du gouvernement du Canada'],
            ['name_en' => 'Skillsoft', 'name_fr' => 'Skillsoft'],
            ['name_en' => 'Vubiz', 'name_fr' => 'Vubiz'],
            ['name_en' => 'Institute of Public Administration', 'name_fr' => 'Institut d\'administration publique'],
        ];
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('content_providers')->truncate();

        ContentProvider::createFrom($this->data());
    }
}
