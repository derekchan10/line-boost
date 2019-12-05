<?php
namespace T8891\LineBoost\Model;

class UserAuthRepository extends BoostBase
{
    public function __construct(UserAuth $model)
    {
        $this->setModel($model);
    }

    /**
     * 獲取用戶授權ID
     *
     * @param string $lineId
     * @return void
     */
    public function getUserAuthId($lineId)
    {
        return $this->model->where('line_id', (string) $lineId)->where('is_del', 0)->orderBy('id', 'DESC')->value('id');
    }

    /**
     * 獲取授權信息
     *
     * @param string $lineId
     * @return void
     */
    public function getAuthInfo($lineId)
    {
        return $this->model->where('line_id', (string) $lineId)->where('is_del', 0)->orderBy('id', 'DESC')->first();
    }

    /**
     * 用 ID 獲取數據
     *
     * @param int $id
     * @return void
     */
    public function getInfo($id)
    {
        return $this->getModel()->find($id);
    }

    /**
     * 創建數據
     *
     * @param object $authInfo
     * @return void
     */
    public function create($authInfo)
    {
        if (!$authInfo) {
            return false;
        }

        $insertData = [
            'compaign' => (string) $this->getCompaign(),
            'line_id' => (string) $authInfo->userId,
            'name' => (string) $authInfo->displayName,
            'headpic' => (string) $authInfo->pictureUrl,
        ];

        $id = $this->model->insertGetId($insertData);

        return $this->getInfo($id);
    }
}