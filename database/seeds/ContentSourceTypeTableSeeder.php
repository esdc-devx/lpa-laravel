<?php

use App\Models\Lists\ContentSourceType;
use Illuminate\Database\Seeder;

class ContentSourceTypeTableSeeder extends Seeder
{
    protected function data() {
        return [
            ['name_en' => 'New', 'name_fr' => 'Nouveau'],
            ['name_en' => 'Replacement', 'name_fr' => 'Remplacement'],
            ['name_en' => 'Split', 'name_fr' => 'Fractionné'],
            ['name_en' => 'Merged', 'name_fr' => 'Fusionné'],
            ['name_en' => 'Refactor', 'name_fr' => 'Restructuré'],
        ];
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Truncate previous tables.
        DB::table('content_source_types')->truncate();

        ContentSourceType::createFrom($this->data());
    }
}
