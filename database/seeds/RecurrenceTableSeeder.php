<?php

use App\Models\Project\BusinessCase\Recurrence;
use Illuminate\Database\Seeder;

class RecurrenceTableSeeder extends Seeder
{
    protected function data()
    {
        return [
            [
                'name_en' => 'Initial Investment',
                'name_fr' => 'Investissement initial',
            ],
            [
                'name_en' => 'Recurrent Cost',
                'name_fr' => 'Coût récurrent',
            ],
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
        DB::table('recurrences')->truncate();

        foreach ($this->data() as $term) {
            Recurrence::create([
                'name_key' => str_slug($term['name_en'], '-'),
                'name_en'  => $term['name_en'],
                'name_fr'  => $term['name_fr'],
            ]);
        }
    }
}
