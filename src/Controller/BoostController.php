<?php
namespace T8891\LineBoost\Controller;

use Illuminate\Http\Request;
use T8891\LineBoost\Exception\LineBoostException;
use T8891\LineBoost\Model\BoostRepository as Boost;
use T8891\LineBoost\Model\SponsorAuthRepository as SponsorAuth;
use T8891\LineBoost\Model\UserAuthRepository as UserAuth;
use T8891\LineBoost\Model\LineService;
use T8891\LineBoost\Event\BoostLineData;
use T8891\LineBoost\Event\BoostUserAuthFinish;
use T8891\LineBoost\Event\BoostBefore;
use T8891\LineBoost\Event\BoostSuccess;

class BoostController
{
    const USER_AUTH_KEY = 'LINE_BOOST_USER_AUTH';

    protected $compaign;
    protected $request;
    protected $boost;
    protected $sponsorAuth;
    protected $userAuth;

    public function __construct(
        Request $request, 
        Boost $boost, 
        SponsorAuth $sponsorAuth, 
        UserAuth $userAuth,
        LineService $lineService
    ) {
        $this->compaign = $request->route('compaign');
        $this->request = $request;
        $this->boost = $boost->setCompaign($this->compaign);
        $this->sponsorAuth = $sponsorAuth->setCompaign($this->compaign);
        $this->userAuth = $userAuth->setCompaign($this->compaign);
        $this->lineService = $lineService;

        if (config('boost.exception_handle')) {
            app()->singleton(
                \Illuminate\Contracts\Debug\ExceptionHandler::class, 
                \T8891\LineBoost\Exception\LineBoostExceptionHandler::class
            );
        }
    }

    /**
     * 助力
     *
     * @return void
     */
    public function boost()
    {
        $id = $this->request->get('id');
        $lineId = $this->request->get('lineId');

        // 助力前置事件
        event(new BoostBefore($id, $lineId));

        // 判斷 LineId 有沒授權過
        $userAuthId = $this->userAuth->getUserAuthId($lineId);
        if (!$userAuthId) {
            return $this->except(config('boost.messages.boost_line_error'));
        }

        // 入庫
        $result = $this->boost->create($id, $userAuthId);
        
        if ($result) {
            $response = ['message' => config('boost.messages.boost_success')];
            return $this->merge($response, event(new BoostSuccess($id, $lineId)));
        } else {
            return $this->except(config('boost.messages.boost_failed'));
        }
    }

    /**
     * 獲取授權信息
     *
     * @param Request $request
     * @return void
     */
    public function lineData(Request $request)
    {
        $response = $this->getUserAuth();

        $id = $request->get('id');
        $response['id'] = (string) $id;

        // 判斷是不是本人
        $sponsorAuthInfo = $this->sponsorAuth->getInfo($id);
        if ($sponsorAuthInfo && $sponsorAuthInfo->line_id == $response['lineId']) {
            $response['isMe'] = 1;
        }
        

        if ($this->boost->checkBoost($id, $response['authId'])) {
            $response['isBoost'] = 1;
        }

        // 獲取助力列表
        $response['boostList'] = $this->boost->getList($id);

        $response = $this->merge($response, event(new BoostLineData($id, $response)));

        return $response;
    }

    /**
     * 助力人授權
     *
     * @return void
     */
    public function userAuth()
    {
        $authInfo = $this->auth();
        
        $result = $this->userAuth->getAuthInfo($authInfo->userId);
        if (!$result) {
            $result = $this->userAuth->create($authInfo);
        }

        // 判斷是不是發起人
        $response = $this->format($result);
        
        $id = $this->request->get('id');

        $sponsorAuthInfo = $this->sponsorAuth->getInfo($id);

        if ($sponsorAuthInfo->line_id == $result->line_id) {
            $response['isMe'] = 1;
        }

        if ($this->boost->checkBoost($id, $response['authId'])) {
            $response['isBoost'] = 1;
        }

        $response = $this->merge($response, event(new BoostUserAuthFinish($id, $response)));

        $this->saveUserAuth($response);

        return $response;
    }

    /**
     * 發起人授權
     *
     * @return void
     */
    public function sponsorAuth()
    {
        $id = $this->request->get('id');
        $authInfo = $this->auth();

        $result = $this->sponsorAuth->getInfo($id);
        $lineAuthInfo = $this->sponsorAuth->getAuthInfo($authInfo->userId);

        // 判斷有無授權數據
        if ($result) {
            // 判斷該頁面的授權是不是這個 Line 賬號
            if ($result->line_id != $authInfo->userId) {
                return $this->except(config('boost.messages.sponsor_auth_page_error'));
            }

            // 判斷 Line ID 有沒授權過其他頁面
            if ($lineAuthInfo->unique_id != $id) {
                return $this->except(config('boost.messages.sponsor_auth_line_error'));
            }
        } elseif ($lineAuthInfo) {
            // 頁面沒有授權信息，Line賬號有授權信息，直接報錯
            return $this->except(config('boost.messages.sponsor_auth_line_error'));
        } else {
            // 沒有的話，直接插入數據
            $result = $this->sponsorAuth->create($id, $authInfo);
        }

        $response = $this->format($result);
        $response['isMe'] = 1;

        return $response;
    }

    /**
     * 授權處理
     *
     * @return void
     */
    protected function auth()
    {
        $code = $this->request->get('code');
        $redirectUri = $this->request->get('redirectUri');

        if (!$code || !$redirectUri) {
            return $this->except(config('boost.messages.param_error'));
        }

        $authInfo = $this->lineService->lineAuth($code, $redirectUri);
        if (!$authInfo) {
            return $this->except(config('boost.messages.auth_error'));
        }

        return $authInfo;
    }

    /**
     * 異常拋出方法
     * @param $msg
     * @param int $status
     * @param array $data
     * @throws LineBoostException
     */
    public function except($msg, $status = 13000, $data = [])
    {
        throw new LineBoostException($msg, $status, $data);
    }

    /**
     * 格式化Line数据
     *
     * @param array $authInfo
     * @return void
     */
    private function format($authInfo)
    {
        $response = [
            'authId' => $authInfo->id,
            'lineId' => $authInfo->line_id,
            'name' => $authInfo->name,
            'headpic' => $authInfo->headpic,
            'isMe' => 0,
            'isBoost' => 0,
        ];

        return $response;
    }

    /**
     * 保存用戶授權
     *
     * @param array $authInfo
     * @return void
     */
    private function saveUserAuth($authInfo)
    {
        $_SESSION[self::USER_AUTH_KEY] = $authInfo;
    }

    /**
     * 獲取用戶授權
     *
     * @return void
     */
    private function getUserAuth()
    {
        return array_merge([
            'id' => 0,
            'authId' => 0,
            'lineId' => '',
            'name' => '',
            'headpic' => '',
            'isMe' => 0,
            'isBoost' => 0,
        ], (array) $_SESSION[self::USER_AUTH_KEY]);
    }

    /**
     * 合併事件回調的數據
     *
     * @param array $response
     * @param array $eventResponse
     * @return void
     */
    private function merge($response, $eventResponse)
    {
        if (!$eventResponse) {
            return $response;
        }

        collect($eventResponse)->each(function ($value) use (&$response) {
            $response = array_merge($response, $value);
        });

        return $response;
    }
}