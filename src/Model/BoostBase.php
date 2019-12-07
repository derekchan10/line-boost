<?php
namespace T8891\LineBoost\Model;

abstract class BoostBase
{
    protected $model;

    protected $compaign;

    /**
     * 定義 Illuminate\Database\Eloquent\Model
     */
    public function __construct($model = null)
    {
        $this->model = $model;
    }

    /**
     * 獲取ORM
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * 設置ORM
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * 設置活動標識
     *
     * @return void
     */
    public function setCompaign($compaign)
    {
        $this->compaign = $compaign;
        return $this;
    }

    /**
     * 獲取活動標識
     *
     * @return void
     */
    public function getCompaign()
    {
        return $this->compaign;
    }
}