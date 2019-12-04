<?php
namespace T8891\LineBoost\Model;

class BoostRepository extends BoostBase
{
    public function __construct(Boosts $model)
    {
        $this->model = $model;
    }

    /**
     * 數據入庫
     *
     * @param string $id
     * @param int $authId
     * @return void
     */
    public function create($id, $authId)
    {
        return $this->model->create([
            'unique_id' => (string) $id, 
            'auth_id' => (int) $authId, 
            'compaign' => $this->getCompaign()
        ]);
    }

    /**
     * 檢測有無授權
     *
     * @param string $id
     * @param int $authId
     * @return void
     */
    public function checkBoost($id, $authId)
    {
        return $this->getModel()->where('unique_id', (string) $id)->where('auth_id', (int) $authId)->count();
    }

    /**
     * 獲取頁面的全部助力數據
     *
     * @param string $id
     * @return void
     */
    public function getList($id)
    {
        $list = $this->model->where('unique_id', (string) $id)->where('is_del', 0)->orderBy('id', 'DESC')->with('auth')->get();

        return $this->format($list);
    }

    /**
     * 格式化助力數據
     *
     * @param ORM $list
     * @return void
     */
    private function format($list)
    {
        $list = $list->map(function ($value) {
            return [
                "lineId" => $value->auth->line_id,
                "name" => $value->auth->name,
                "headpic" => $value->auth->headpic,
            ];
        });

        return $list;
    }
}