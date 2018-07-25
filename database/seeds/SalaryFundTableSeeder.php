<?php

use App\Models\Project\BusinessCase\SalaryFund;
use Illuminate\Database\Seeder;

class SalaryFundTableSeeder extends Seeder
{
    protected function data()
    {
        return [
            [
                'name_en' => '$0',
                'name_fr' => '0 $',
            ],
            [
                'name_en' => '$1 - $49 999',
                'name_fr' => '1 $ à 49 999 $',
            ],
            [
                'name_en' => '$50 000 - $99 999',
                'name_fr' => '50 000 $ à 99 999 $',
            ],
            [
                'name_en' => '$100 000 - $249 999',
                'name_fr' => '100 000 $ à 249 999 $',
            ],
            [
                'name_en' => '$250 000 - $499 999',
                'name_fr' => '250 000 $ à 499 999 $',
            ],
            [
                'name_en' => '$500 000 - $999 999',
                'name_fr' => '500 000 $ à 999 999 $',
            ],
            [
                'name_en' => '$1 000 000 and over',
                'name_fr' => '1 000 000 $ et plus',
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
        DB::table('salary_funds')->truncate();

        foreach ($this->data() as $term) {
            SalaryFund::create([
                'name_key' => str_slug($term['name_en'], '-'),
                'name_en'  => $term['name_en'],
                'name_fr'  => $term['name_fr'],
            ]);
        }
    }
}
