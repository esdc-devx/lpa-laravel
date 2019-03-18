<?php

use App\Models\Lists\TargetAudienceKnowledgeLevel;
use Illuminate\Database\Seeder;

class TargetAudienceKnowledgeLevelTableSeeder extends Seeder
{
    protected function data()
    {
        return [
            ['name_en' => 'Beginner', 'name_fr' => 'Débutant'],
            ['name_en' => 'Intermediate', 'name_fr' => 'Intermédiaire'],
            ['name_en' => 'Advanced', 'name_fr' => 'Avancé'],
        ];
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('target_audience_knowledge_levels')->truncate();

        TargetAudienceKnowledgeLevel::createFrom($this->data());
    }
}
