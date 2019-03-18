<?php

use App\Models\Lists\MaterialItem;
use Illuminate\Database\Seeder;

class MaterialItemTableSeeder extends Seeder
{
    protected function data()
    {
        return [
            ['name_en' => 'Computer', 'name_fr' => 'Ordinateur'],
            ['name_en' => 'DVD Player', 'name_fr' => 'Lecteur DVD'],
            ['name_en' => 'Eraser', 'name_fr' => 'Gomme à effacer'],
            ['name_en' => 'Flip Chart', 'name_fr' => 'Tableau à feuilles'],
            ['name_en' => 'Flip Chart Marker', 'name_fr' => 'Marqueur pour tableau à feuilles'],
            ['name_en' => 'Highlighter', 'name_fr' => 'Surligneur'],
            ['name_en' => 'Interactive Touch Screen', 'name_fr' => 'Écran tactile interactif'],
            ['name_en' => 'Internet Access', 'name_fr' => 'Accès Internet'],
            ['name_en' => 'Paper Pad ', 'name_fr' => 'Calepin '],
            ['name_en' => 'Pen', 'name_fr' => 'Stylo'],
            ['name_en' => 'Pencil', 'name_fr' => 'Crayon'],
            ['name_en' => 'Post-Its ', 'name_fr' => 'Papillons adhésifs '],
            ['name_en' => 'Presentation Remote Control ', 'name_fr' => 'Télécommande pour présentation '],
            ['name_en' => 'Projector/screen ', 'name_fr' => 'Projecteur/écran '],
            ['name_en' => 'Tape Roll', 'name_fr' => 'Ruban adhésif'],
            ['name_en' => 'Tent Card (Name Card)', 'name_fr' => 'Carton-tente (porte-nom)'],
            ['name_en' => 'Whiteboard', 'name_fr' => 'Tableau blanc'],
            ['name_en' => 'Whiteboard Marker', 'name_fr' => 'Marqueur pour tableau blanc'],
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
        DB::table('material_items')->truncate();

        MaterialItem::createFrom($this->data());
    }
}
