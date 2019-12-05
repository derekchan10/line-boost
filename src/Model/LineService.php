<?php
namespace T8891\LineBoost\Model;

class LineService
{
    const CLIENT_ID = "1536689177";
    const CLIENT_SECRET = "f5dd273424e31d7efdcacb584bc96ba5";
    const GRANT_TYPE = "authorization_code";
    const ACCESSTOKEN_URL = "https://api.line.me/oauth2/v2.1/token";
    const PROFILE_URL = "https://api.line.me/v2/profile";


    private $redirect_uri = "https://c.8891.com.tw/photoUser-lineLogin.html";


    /**
     * Line 授權
     *
     * @param string $code
     * @param string $redirectUri
     * @return void
     */
    public function lineAuth($code, $redirectUri = '')
    {
        if (!empty($code)) {
            $this->redirect_uri = $redirectUri ? $redirectUri : $this->redirect_uri;

            //Send data to LINE API
            $data = array(
                'grant_type' => self::GRANT_TYPE,
                'code' => $code,
                'client_id' => self::CLIENT_ID,
                'client_secret' => self::CLIENT_SECRET,
                'redirect_uri' => $this->redirect_uri,
            );

            $header = [
                "Content-type: application/x-www-form-urlencoded"
            ];
            
            $response = $this->curl(self::ACCESSTOKEN_URL, 1, $header, $data);

            //Decode the json
            $obj = json_decode(trim($response));
            if($obj->error){
                return false;
            }

            //Get access token
            $access_token = $obj->access_token;


            //Send access_token to API
            $header = [
                "Authorization: Bearer " . $access_token
            ];
            $response = $this->curl(self::PROFILE_URL, false, $header, []);
            $data = json_decode($response);

            return $data;

        } else {
            return false;
        }
    }

    /**
     * 獲取 Line Web Login URL
     *
     * @return void
     */
    public function getLineWebLoginUrl()
    {
        $encodedCallbackUrl = urlencode($this->redirect_uri);
        $state = mt_rand(111111, 999999);
        $_SESSION['LINE_LOGIN_STATE'] = $state;
        return "https://access.line.me/oauth2/v2.1/authorize?response_type=code" . "&client_id=" . self::CLIENT_ID . "&redirect_uri=" . $encodedCallbackUrl . "&state=" . $state . "&scope=email%20openid%20profile";
    }

    /**
     * 請求 Line 授權
     *
     * @param string $url
     * @param string $method
     * @param array $header
     * @param array $data
     * @return void
     */
    private function curl($url, $method, $header, $data){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 

        if($data){
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }
        
        $response = curl_exec($ch);
        curl_close ( $ch );
        return $response;
    }
}