<?php
namespace T8891\LineBoost\Model;

class BoostRepository extends BoostBase
{
    public function __construct(Boost $model)
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
        return $this->model->insert([
            'unique_id' => (string) $id, 
            'auth_id' => (int) $authId, 
            'compaign' => $this->getCompaign()
        ]);
    }

    /**
     * Undocumented function
     *
     * @param string $id
     * @return void
     */
    public function getTodayBoostNum($id)
    {
        return $this->getModel()->where('unique_id', (string) $id)->where('is_del', 0)->whereDate('add_time', date('Y-m-d'))->count();
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
        return $this->getModel()->where('unique_id', (string) $id)->where('auth_id', (int) $authId)->where('is_del', 0)->count();
    }

    /**
     * 檢測今天有無助力
     *
     * @return void
     */
    public function checkTodayBoost($id, $authId)
    {
        return $this->getModel()
            ->where('unique_id', (string) $id)
            ->where('auth_id', (int) $authId)
            ->whereDate('add_time', date('Y-m-d'))
            ->where('is_del', 0)
            ->count();
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
                "time" => $value->add_time,
            ];
        });

        return $list;
    }
}