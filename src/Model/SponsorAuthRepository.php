<?php
namespace T8891\LineBoost\Model;

class SponsorAuthRepository extends BoostBase
{
    public function __construct(SponsorAuth $model)
    {
        $this->model = $model;
    }

    /**
     * 獲取授權詳情
     *
     * @param string $id
     * @return void
     */
    public function getInfo($id)
    {
        return $this->getModel()->where('unique_id', (string) $id)->where('is_del', 0)->first();
    }
    
    /**
     * 用 Line ID 獲取詳情
     *
     * @param string $lineId
     * @return void
     */
    public function getAuthInfo($lineId)
    {
        return $this->getModel()->where('line_id', (string) $lineId)->where('is_del', 0)->first();
    }

    /**
     * 創建數據
     *
     * @param object $authInfo
     * @return void
     */
    public function create($id, $authInfo)
    {
        if (!$id || !$authInfo) {
            return false;
        }

        $insertData = [
            'unique_id' => (string) $id,
            'compaign' => (string) $this->getCompaign(),
            'line_id' => (string) $authInfo->userId,
            'name' => (string) $authInfo->displayName,
            'headpic' => (string) $authInfo->pictureUrl,
        ];

        $this->model->insertGetId($insertData);

        return $this->getInfo($id);
    }
}