<?php namespace Johnrich85\EloquentQueryModifier\Modifiers;

class FieldSelectionModifier extends BaseModifier {


    /**
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws \Exception
     */
    public function modify() {
        $fields = $this->fetchValuesFromData();

        if($fields === false) {
            return $this->builder;
        }
        else if($fields == '') {
            $this->throwNoDataException();
        }

        $fields = $this->listToArray($fields);

        $this->checkForInvalidFields($fields);

        return $this->builder->select($fields);
    }

    /**
     * Checks for fields that do not exist, throws
     * exception if found.
     *
     * @param $fields
     * @throws \Exception
     */
    protected function checkForInvalidFields($fields) {
        $allowedFields = $this->config->getFilterableFields();

        foreach($fields as $field) {
            if(empty($allowedFields[$field])) {
                $this->throwInvalidFieldException($field);
            }
        }

        return false;
    }

    /**
     * Pulls field selection data from array.
     *
     * @return bool
     */
    protected function fetchValuesFromData() {
        $fieldSelectionIndex = $this->config->getFields();

        if(empty($this->data[$fieldSelectionIndex])) {
            return false;
        }

        return $this->data[$fieldSelectionIndex];
    }
}
