<?php namespace Johnrich85\EloquentQueryModifier\Modifiers;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOneOrMany;
use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Laracasts\TestDummy\EloquentModel;

class FieldSelectionModifier extends BaseModifier
{

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws \Exception
     */
    public function modify()
    {
        $fields = $this->fetchValuesFromData();

        if ($fields === false) {
            return $this->builder;
        } else {
            if ($fields == '') {
                $this->throwNoDataException();
            }
        }

        $fields = $this->listToArray($fields);

        $fields = $this->getValidFields($fields);

        return $this->builder->select($fields);
    }

    /**
     * Checks for fields that do not exist, throws
     *
     * @param $fields
     * @return bool
     * @throws \Exception
     */
    protected function getValidFields($fields)
    {
        $allowedFields = $this->config->getFilterableFields();

        $hasEagerLoad = $this->hasEagerLoad();

        foreach ($fields as $key => $field) {
            if (!empty($allowedFields[$field])) {
                continue;
            }

            if ($hasEagerLoad) {
                unset($fields[$key]);
            } else {
                $this->throwInvalidFieldException($field);
            }
        }

        if (empty($fields)) {
            $fields = $this->getDefaultValue();
        } else {
            $fields = $this->addRequiredFields($fields);
        }

        return $fields;
    }

    /**
     * Adds required fields to query (eager loading
     * requires that keys be selected, else related
     * models are not returned.)
     *
     * @param array $fields
     * @return array
     */
    public function addRequiredFields(array $fields)
    {
        $relations = $this->builder->getEagerLoads();

        foreach ($relations as $name => $relation) {
            $relation = $this->builder->getRelation($name);

            $requiredColumns = $this->addKeys($relation);

            $fields = array_merge($fields, $requiredColumns);
        }

        return array_unique($fields);
    }

    /**
     * Returns keys as an array.
     *
     * @param $relation
     * @return array
     */
    protected function addKeys($relation)
    {
        $payload = [];

        if ($relation instanceof MorphTo) {
            $payload[] = $relation->getForeignKey();
            $payload[] = $relation->getMorphType();
        } elseif ($relation instanceof MorphToMany ||
            $relation instanceof BelongsToMany ||
            $relation instanceof MorphOneOrMany
        ) {
            $payload[] = $this->builder->getModel()->getKeyName();
        } elseif ($relation instanceof BelongsTo) {
            $payload[] = $relation->getForeignKey();
        } elseif ($relation instanceof MorphPivot) {
            $payload[] = $relation->getForeignKey();
            $payload[] = $relation->getMorphClass();
            $payload[] = $this->builder->getModel()->getKeyName();
        } elseif ($relation instanceof Pivot) {
            $payload[] = $relation->getForeignKey();
            $payload[] = $relation->getOtherKey();
            $payload[] = $this->builder->getModel()->getKeyName();
        } else {
            $payload[] = $this->builder->getModel()->getKeyName();
        }

        return $payload;
    }
    /**
     * @return array
     */
    protected function getDefaultValue()
    {
        return ['*'];
    }

    /**
     * Pulls field selection data from array.
     *
     * @return bool
     */
    protected function fetchValuesFromData()
    {
        $fieldSelectionIndex = $this->config->getFields();

        if (empty($this->data[$fieldSelectionIndex])) {
            return false;
        }

        return $this->data[$fieldSelectionIndex];
    }
}
