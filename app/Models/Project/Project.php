<?php

namespace App\Models\Project;

use App\Events\ProcessEntityDeleted;
use App\Models\BaseModel;
use App\Models\LearningProduct\LearningProduct;
use App\Models\OrganizationalUnit;
use App\Models\Project\BusinessCase\BusinessCase;
use App\Models\Project\GateOneApproval\GateOneApproval;
use App\Models\Project\PlannedProductList\PlannedProductList;
use App\Models\State;
use App\Models\Traits\HasProcesses;
use App\Models\Traits\UsesUserAudit;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends BaseModel
{
    use SoftDeletes, UsesUserAudit, HasProcesses;

    protected $fillable = [
        'name',
        'organizational_unit_id',
        'state_id',
        'process_instance_id',
        'business_case_id',
        'planned_product_list_id',
        'gate_one_approval_id',
        'created_by',
        'updated_by',
    ];

    protected $dates = [
        'deleted_at',
    ];

    public function businessCase()
    {
        return $this->belongsTo(BusinessCase::class);
    }

    public function plannedProductList()
    {
        return $this->belongsTo(PlannedProductList::class);
    }

    public function gateOneApproval()
    {
        return $this->belongsTo(GateOneApproval::class);
    }

    public function learningProducts()
    {
        return $this->hasMany(LearningProduct::class);
    }

    /**
     * Return a filtered list of projects available for learning product creation.
     *
     * @return Illuminate\Support\Collection
     */
    public static function availableForLearningProductCreation()
    {
        return static::whereState(['approved', 'active'])
            ->with('plannedProductList.plannedProducts', 'learningProducts')
            ->where('process_instance_id', null)
            ->whereIn('organizational_unit_id', OrganizationalUnit::getLearningProductOwnersFor(auth()->user())->pluck('id'))
            ->get()->filter(function ($project)  {
                return $project->available_learning_product_types->isNotEmpty();
            })->values();
    }

    /**
     * Add an accessor to get a list of learning product types
     * available for creation.
     *
     * @return Illuminate\Support\Collection
     */
    public function getAvailableLearningProductTypesAttribute()
    {
        // Ensure there is some planned products.
        if (! $this->plannedProductList || $this->plannedProductList->plannedProducts->isEmpty()) {
            return collect([]);
        }

        // Group all project learning products by their sub type ids.
        $projectLearningProducts = $this->learningProducts->groupBy('sub_type_id');

        // For each planned products, substract their quantity from the number of products already created.
        return $this->plannedProductList->plannedProducts->map(function ($plannedProduct) use ($projectLearningProducts) {
            // Work with a copy of the object to keep the original values intact.
            $plannedProduct = $plannedProduct->replicate();
            if ($projectLearningProducts->has($plannedProduct->sub_type_id)) {
                $plannedProduct->quantity -= $projectLearningProducts->get($plannedProduct->sub_type_id)->count();
            }
            return $plannedProduct;
        })
        // Filter out product types which are fully created already.
        ->whereNotIn('quantity', [0])->flatten()->map(function ($productType) {
            return $productType->only(['type_id', 'sub_type_id', 'quantity']);
        });
    }
}
