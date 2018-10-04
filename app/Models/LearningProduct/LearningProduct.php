<?php

namespace App\Models\LearningProduct;

use App\Events\LearningProductCreated;
use App\Events\LearningProductDeleted;
use App\Models\BaseModel;
use App\Models\Project\Project;
use App\Models\Traits\HasProcesses;
use App\Models\Traits\UsesUserAudit;
use App\Models\User\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nanigans\SingleTableInheritance\SingleTableInheritanceTrait;

class LearningProduct extends BaseModel
{
    use SoftDeletes, UsesUserAudit, HasProcesses, SingleTableInheritanceTrait;

    // Indicate which field is used to store the type of each class.
    protected static $singleTableTypeField = 'entity_type';

    // Model classes that will inherit from this class.
    protected static $singleTableSubclasses = [
        Course::class,
    ];

    // Fields that will be retrieved and updated from all models.
    protected static $persisted = [
        'entity_type', 'name', 'type_id', 'sub_type_id', 'organizational_unit_id', 'project_id', 'state_id', 'process_instance_id',
        'primary_contact', 'manager', 'created_by', 'updated_by',
    ];

    protected $table = 'learning_products';

    protected $hidden = [
        'type_id', 'sub_type_id', 'organizational_unit_id', 'state_id', 'process_instance_id',
    ];

    protected $fillable = [
        'name', 'type_id', 'sub_type_id', 'organizational_unit_id', 'project_id', 'state_id', 'process_instance_id',
        'primary_contact', 'manager', 'created_by', 'updated_by',
    ];

    protected $dates = [
        'deleted_at',
    ];

    protected $dispatchesEvents = [
        'created' => LearningProductCreated::class,
        'deleted' => LearningProductDeleted::class,
    ];

    public function type()
    {
        return $this->belongsTo(LearningProductType::class);
    }

    public function subType()
    {
        return $this->belongsTo(LearningProductType::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function primaryContact()
    {
        return $this->belongsTo(User::class, 'primary_contact');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager');
    }
}
