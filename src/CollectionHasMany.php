<?php

namespace Victorstack\LaravelCollectionHasMany;

use Illuminate\Support\Collection;

class CollectionHasMany
{
    /**
     * Register the hasMany macro on the Collection class.
     *
     * @return void
     */
    public static function register()
    {
        if (Collection::hasMacro('hasMany')) {
            return;
        }

        Collection::macro('hasMany', function ($items, $foreignKey, $localKey = 'id', $relationName = 'items') {
            /** @var Collection $this */
            
            // Normalize items to a collection
            $relatedCollection = $items instanceof Collection ? $items : Collection::make($items);

            // Group related items by their foreign key for O(1) lookup during iteration
            $grouped = $relatedCollection->groupBy($foreignKey);

            return $this->map(function ($parent) use ($grouped, $localKey, $relationName) {
                // Resolve local key value
                $parentId = data_get($parent, $localKey);

                // Get related items or empty collection
                $children = $grouped->get($parentId, new Collection());

                // Attach to parent
                if (is_array($parent)) {
                    $parent[$relationName] = $children;
                } elseif (is_object($parent)) {
                    $parent->{$relationName} = $children;
                }

                return $parent;
            });
        });
    }
}
