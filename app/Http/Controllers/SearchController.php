<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserLdap;
use App\Models\LocalizableModel;
use App\Models\User\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class SearchController extends APIController
{
    protected $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    /**
     * Exclude invalid model attributes and handle localizable attributes.
     *
     * @param  Request $request
     * @param  App\Models\BaseModel $entity
     * @return \Illuminate\Support\Collection
     */
    protected function resolveSearchParameters($request, $entity)
    {
        $params = [];
        collect($request->all())
        ->each(function ($value, $field) use ($entity, &$params) {
            // Add locale to localizable attributes.
            if ($entity instanceof LocalizableModel && in_array($field, $entity->getLocalizableAttributes())) {
                $params[$field .= '_' . app()->getLocale()] = $value;
            }
            // Ignore invalid attributes.
            if (! $entity->hasAttribute($field)) {
                return;
            }
            $params[$field] = $value;
        });
        return collect($params);
    }

    /**
     * Search for an entity having fields matching querystring parameters.
     *
     * @param  Request $request
     * @param  App\Models\BaseModel $entity
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request, $entity)
    {
        $attributes = $this->resolveSearchParameters($request, $entity);
        $results = [];

        // Only execute search query if valid attributes are found.
        if ($attributes->isNotEmpty()) {
            $results = $entity::where(function ($query) use ($attributes) {
                $attributes->each(function ($value, $field) use ($query) {
                    $query->where($field, 'like', "%$value%");
                });
            })->get();
        }

        return $this->respond(
            $results
        );
    }

    /**
     * Search users into LDAP repository.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function ldap(Request $request)
    {
        $this->authorize('search', User::class);

        $results = $this->users->searchLdap($request->get('name'));
        return $this->respond(
            UserLdap::collection($results)
        );
    }
}
