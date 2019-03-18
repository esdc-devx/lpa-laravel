<?php

use App\Models\Lists\DocumentCategory;
use Illuminate\Database\Seeder;

class DocumentCategoryTableSeeder extends Seeder
{
    protected function data()
    {
        return [
            ['name_en' => 'Poster', 'name_fr' => 'Affiche'],
            ['name_en' => 'Presentation Slide', 'name_fr' => 'Diapositive'],
            ['name_en' => 'Instructor Manual', 'name_fr' => 'Manuel de l’instructeur'],
            ['name_en' => 'Pre-Course Material', 'name_fr' => 'Documents à lire au préalable'],
            ['name_en' => 'Participant Manual', 'name_fr' => 'Manuel du participant'],
            ['name_en' => 'Reference Manual', 'name_fr' => 'Manuel de référence'],
            ['name_en' => 'Hand-out', 'name_fr' => 'Document à distribuer'],
            ['name_en' => 'Post-Course Material', 'name_fr' => 'Documents à lire après le cours'],
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
        DB::table('document_categories')->truncate();

        DocumentCategory::createFrom($this->data());
    }
}
