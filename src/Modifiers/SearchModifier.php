<?php namespace Johnrich85\EloquentQueryModifier\Modifiers;

class SearchModifier extends BaseModifier
{
    /**
     * The sort string pulled from the
     * query string.
     *
     * @var String
     */
    protected $search;

    /**
     * @var string
     */
    protected $searchMode;

    /**
     * Adds sorting to query-builder (if data
     * provided contains sort info)
     *
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws \Exception
     */
    public function modify()
    {
        $this->search = $this->fetchValuesFromData();
        $this->searchMode = $this->config->getSearchMode();

        if (empty($this->search)) {
            return $this->builder;
        }
        
        return $this->addSearchToQueryBuilder();
    }

    /**
     * Gets the sort values from the data
     * provided. Returns false if not
     * found.
     *
     * @return bool|array
     */
    protected function fetchValuesFromData()
    {
        $paramName = $this->config->getSearch();

        if (!isset($this->data[$paramName])) {
            return false;
        }

        return $this->data[$paramName];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function addSearchToQueryBuilder()
    {
        try {
            if ($this->searchMode == 'wildcard') {
                $this->builder->search($this->search);
            } else {
                $this->builder->search($this->search, false);
            }
        } catch (\BadMethodCallException $e) {
            $class = get_class($this->builder->getModel());

            $this->throwSearchNotSupportedException($class);
        }

        return $this->builder;
    }
}
