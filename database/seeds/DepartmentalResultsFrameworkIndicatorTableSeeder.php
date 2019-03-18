<?php

use App\Models\Lists\DepartmentalResultsFrameworkIndicator;
use Illuminate\Database\Seeder;

class DepartmentalResultsFrameworkIndicatorTableSeeder extends Seeder
{
    protected function data()
    {
        return [
            [
                'name_key' => 'indicator-1',
                'name_en' => '1) % of learning priorities addressed annually',
                'name_fr' => '1) % des priorités d’apprentissage abordées sur une base annuelle',
            ],
            [
                'name_key' => 'indicator-2',
                'name_en' => '2) % of learning products updated in accordance with the product lifecycle plan',
                'name_fr' => '2) % des produits d’apprentissage actualisés conformément au plan sur le cycle de vie des produits',
            ],
            [
                'name_key' => 'indicator-3',
                'name_en' => '3) % of learners who reported that their common learning needs were met',
                'name_fr' => '3) % d’apprenants ayant déclaré que leurs besoins d’apprentissage ont été satisfaits',
            ],
            [
                'name_key' => 'indicator-4',
                'name_en' => '4) % of supervisors who report improved performance of employees; in particular for those employees in management and leadership development programs',
                'name_fr' => '4) % de superviseurs qui ont signalé l’amélioration du rendement des employés; plus précisément pour ceux et celles qui participent aux programmes de perfectionnement en leadership et en gestion',
            ],
            [
                'name_key' => 'indicator-5',
                'name_en' => '5) % of learners who report that the facilitator/instructor was effective',
                'name_fr' => '5) % d’apprenants ayant indiqué que l’animateur/l’instructeur était efficace',
            ],
            [
                'name_key' => 'indicator-6',
                'name_en' => '6) % of employees of the public service who access common learning annually',
                'name_fr' => '6) % des employés de la fonction publique qui accèdent à l’apprentissage commun sur une base annuelle',
            ],
            [
                'name_key' => 'indicator-7',
                'name_en' => '7) % of employees of the public service in the National Capital Region who access common learning annually',
                'name_fr' => '7) % des employés de la fonction publique ans la région de la capitale nationale qui accèdent à l’apprentissage commun sur une base annuelle',
            ],
            [
                'name_key' => 'indicator-8',
                'name_en' => '8) % of employees of the public service in Canada outside of the National Capital Region who access common learning annually',
                'name_fr' => '8) % des employés de la fonction publique au Canada à l’extérieur de la région de la capitale nationale qui accèdent à l’apprentissage commun sur une base annuelle',
            ],
            [
                'name_key' => 'indicator-9',
                'name_en' => '9) # of demonstration and learning projects undertaken by the Canada School of Public Service each year',
                'name_fr' => '9) # de démonstrations et de projets d’apprentissage exécutés chaque année par l’École de la fonction publique du Canada',
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
        DB::table('business_case_departmental_results_framework_indicator')->truncate();
        DB::table('departmental_results_framework_indicators')->truncate();

        DepartmentalResultsFrameworkIndicator::createFrom($this->data());
    }
}
