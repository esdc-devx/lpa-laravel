<?php

use App\Models\LearningProduct\LearningProduct;
use App\Models\LearningProduct\LearningProductType;
use App\Models\Project\Project;
use App\Models\State;
use Illuminate\Database\Seeder;

class LearningProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LearningProduct::truncate();

        // Fetch all project that were previously approved with their planned products.
        Project::whereState('approved')->with('architecturePlan.plannedProducts')->get()
            ->each(function ($project) {
                // For each planned product types, create a random number of learning products.
                $project->architecturePlan->plannedProducts->each(function ($product) use ($project) {
                    $productType = LearningProductType::find($product->type_id)->name_key;
                    if ($quantity = rand(0, $product->quantity)) {
                        factory(entity_class($productType), $quantity)->create([
                            'sub_type_id'            => $product->sub_type_id,
                            'project_id'             => $project->id,
                            'organizational_unit_id' => $project->organizational_unit_id,
                        ]);
                    }
                });
            });
    }
}
