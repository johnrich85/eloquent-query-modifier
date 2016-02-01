<?php namespace Johnrich85\EloquentQueryModifier\Modifiers;

class PagingModifier extends BaseModifier {

    /**
     * Num items per page.
     *
     * @var int
     * @todo - need to make this configurable, rather than static
     */
    protected $perPage = 10;

    /**
     * @var
     */
    protected $pageNum;

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws \Exception
     */
    public function modify() {
        $this->pageNum = $this->fetchValuesFromData();

        if($this->pageNum === false) {
            return $this->builder;
        }
        else if($this->pageNum == '') {
            $this->throwNoDataException();
        }

        return $this->addPagingToQueryBuilder();
    }

    /**
     * @return mixed
     */
    protected function addPagingToQueryBuilder() {

        if($this->pageNum < 1) {
            $this->pageNum = 1;
        }

        $offset = ($this->pageNum - 1) * $this->perPage;

        $this->builder = $this->builder
            ->take($this->perPage)
            ->skip($offset);

        return $this->builder;
    }

    /**
     * @return bool
     */
    protected function fetchValuesFromData() {
        $sortIndex = $this->config->getPage();

        if(!isset($this->data[$sortIndex])) {
            return false;
        }

        return $this->data[$sortIndex];
    }

    /**
     * @return mixed
     */
    public function getPageNum()
    {
        return $this->pageNum;
    }

    /**
     * @param mixed $pageNum
     */
    public function setPageNum($pageNum)
    {
        $this->pageNum = $pageNum;
    }

}
