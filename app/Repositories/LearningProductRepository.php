<?php

namespace App\Repositories;

use App\Models\LearningProduct\LearningProduct;
use App\Models\LearningProduct\LearningProductType;
use App\Models\State;
use App\Repositories\UserRepository;
use DB;

class LearningProductRepository extends BaseEloquentRepository
{
    protected $model = LearningProduct::class;
    protected $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
        parent::__construct();
    }

    /**
     * Resolve learning product model class from type id.
     *
     * @param  int $typeId
     * @return App\Models\BaseModel
     */
    protected function resolveModel($typeId)
    {
        return entity(
            LearningProductType::find($typeId)->name_key
        );
    }

    /**
     * Resolve state for learning product type.
     *
     * @param  string $state
     * @return int
     */
    protected function resolveState($stateKey)
    {
        return State::getIdFromKey("{$this->model->getEntityType()}.$stateKey");
    }

    /**
     * Create learning product.
     *
     * @param  array $data
     * @return mixed | Learning product type class (Course, Event, Video, etc.).
     */
    public function create(array $data)
    {
        try {
            DB::beginTransaction();

            // Resolve product type model class from type id.
            $this->model = $this->resolveModel($data['type_id']);

            $learningProduct = $this->model->create(
                array_merge($data, [
                    'state_id'        => $this->resolveState('new'),
                    'primary_contact' => $this->users->findOrCreate($data['primary_contact'])->id,
                    'manager'         => $this->users->findOrCreate($data['manager'])->id,
                    'created_by'      => auth()->user()->id,
                    'updated_by'      => auth()->user()->id,
                ])
            );
        }
        // Rollback transaction if any exceptions occurs.
        catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        DB::commit();

        // Return newly created learning product.
        return $learningProduct;
    }

    /**
     * Update learning product.
     *
     * @param  array $data
     * @return mixed | Learning product type class (Course, Event, Video, etc.).
     */
    public function update($id, array $data = [])
    {
        try {
            DB::beginTransaction();

            // Resolve product type model class from type id.
            $this->model = $this->resolveModel($this->getById($id)->type_id);

            $learningProduct = $this->model->whereId($id)->update(
                array_merge($data, [
                    'primary_contact' => $this->users->findOrCreate($data['primary_contact'])->id,
                    'manager'         => $this->users->findOrCreate($data['manager'])->id,
                    'updated_by'      => auth()->user()->id,
                ])
            );
        }
        // Rollback transaction if any exceptions occurs.
        catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        DB::commit();

        // Return updated learning product.
        return $learningProduct;
    }
}
