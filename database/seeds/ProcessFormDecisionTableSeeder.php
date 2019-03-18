<?php

use Illuminate\Database\Seeder;
use App\Models\Process\ProcessFormDecision;

class ProcessFormDecisionTableSeeder extends Seeder
{
    protected function data()
    {
        return [
            [
                'name_key' => 'approved',
                'name_en'  => 'Approved',
                'name_fr'  => 'Approuvé',
            ],
            [
                'name_key' => 'rejected',
                'name_en'  => 'Requires Adjustments',
                'name_fr'  => 'Nécessite des ajustements',
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
        DB::table('process_form_decisions')->truncate();

        ProcessFormDecision::createFrom($this->data());
    }
}
