<?php

namespace App\Models\Project;

use App\Models\BaseModel;
use App\Models\LearningProduct\LearningProduct;
use App\Models\OrganizationalUnit;
use App\Models\Project\BusinessCase\BusinessCase;
use App\Models\Project\GateOneApproval\GateOneApproval;
use App\Models\Project\PlannedProductList\PlannedProductList;
use App\Models\State;
use App\Models\Traits\HasInformationSheets;
use App\Models\Traits\HasProcesses;
use App\Models\Traits\UsesUserAudit;
use App\Models\User\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends BaseModel
{
    use SoftDeletes, UsesUserAudit, HasProcesses, HasInformationSheets;

    protected $fillable = [
        'name',
        'outline',
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
        // Get active or approved projects with their planned products and their current products.
        $projects = static::whereState(['approved', 'active'])
            ->with('plannedProductList.plannedProducts', 'learningProducts');

        // Exclude projects with a current process.
        $projects->where('process_instance_id', null);

        // If user is authenticad, filter projects against his available organizational units.
        if (auth()->user()) {
            $organizationalUnits = OrganizationalUnit::getLearningProductOwnersFor(auth()->user());
            $projects->whereIn('organizational_unit_id', $organizationalUnits->pluck('id'));
        }

        // Return all projects available for learning product creation.
        return $projects->get()->filter(function ($project)  {
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
