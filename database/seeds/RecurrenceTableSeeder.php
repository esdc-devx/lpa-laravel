<?php

use App\Models\Lists\Recurrence;
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

        Recurrence::createFrom($this->data());
    }
}
