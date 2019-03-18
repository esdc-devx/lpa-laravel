<?php

namespace App\Models;

use App\Models\Traits\UsesKeyNameField;

class ListableModel extends LocalizableModel
{
    use UsesKeyNameField;

    protected $fillable = [
        'parent_id',
        'name_key',
        'name_en',
        'name_fr',
        'active',
    ];

    protected $hidden = [
        'pivot',
    ];

    protected $localizable = [
        'name',
    ];

    protected $parent = 'parent_id';

    public $timestamps = false;

    /**
     * Generate list terms from data array.
     *
     * @param  array $data
     * @return void
     */
    public static function createFrom($data)
    {
        foreach ($data as $term) {
            $termId = static::createTerm($term);
            // Handle two levels list.
            if (isset($term['children'])) {
                foreach ($term['children'] as $child) {
                    $child['parent_id'] = $termId;
                    static::createTerm($child);
                }
            }
        }
    }

    /**
     * Create a list term.
     *
     * @param  array $term
     * @return int
     */
    protected static function createTerm($term)
    {
        return static::create([
            'parent_id' => $term['parent_id'] ?? 0,
            'name_key'  => $term['name_key'] ?? str_slug($term['name_en'], '-'),
            'name_en'   => $term['name_en'],
            'name_fr'   => $term['name_fr'],
        ])->id;
    }

    /**
     * Render the model formatted for lists.
     *
     * @return array
     */
    public static function toListArray()
    {
        return nestable(
            static::where('active', 1)->get()->toArray()
        )->renderAsArray();
    }
}
