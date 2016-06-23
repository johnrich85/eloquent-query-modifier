<?php namespace Johnrich85\EloquentQueryModifier\Modifiers;

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
        }

        return $fields;
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
