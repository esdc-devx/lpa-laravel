<?php

use App\Models\Lists\Program;
use Illuminate\Database\Seeder;

class ProgramTableSeeder extends Seeder
{
    protected function data()
    {
        return [
            [
                'name_en' => 'Aspiring Directors',
                'name_fr' => 'Futurs directeurs',
            ],
            [
                'name_en' => 'Authority Delegation Training',
                'name_fr' => 'Formation sur la délégation de pouvoirs',
            ],
            [
                'name_en' => 'Executive Programs',
                'name_fr' => 'Programmes à l’intention des cadres',
                'children' => [
                    [
                        'name_en' => 'Directors General Program',
                        'name_fr' => 'Programme à l’intention des directeurs généraux',
                    ],
                    [
                        'name_en' => 'Executive Leadership Development Program for EX-01 to EX-03',
                        'name_fr' => 'Programme de développement en leadership pour les cadres supérieurs EX-01 à EX-03',
                    ],
                    [
                        'name_en' => 'Executive Leadership Development Program for EX-04 and EX-05',
                        'name_fr' => 'Programme de développement en leadership pour les cadres supérieurs EX-04 et EX-05',
                    ],
                    [
                        'name_en' => 'New Directors Program',
                        'name_fr' => 'Programme à l’intention des nouveaux directeurs',
                    ],
                ]
            ],
            [
                'name_en' => 'Manager Development Program',
                'name_fr' => 'Programme de perfectionnement des gestionnaires',
            ],
            [
                'name_en' => 'Supervisor Development Program',
                'name_fr' => 'Programme de perfectionnement des superviseurs',
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
        DB::table('design_program')->truncate();
        DB::table('programs')->truncate();

        Program::createFrom($this->data());
    }
}
