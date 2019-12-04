<?php
/**
 * Api異常處理組件
 *
 * @author Derek Chan <dchan0831@gmail.com>
 * @version 2018-11-12
 */

namespace T8891\LineBoost\Exception;

class LineBoostException extends \Exception
{

    protected $data;

    public function __construct($message, $code = 400, $data = [], \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->data = $data;
    }

    public function getMessages()
    {
        return array(
            'message' => $this->getMessage(),
            'data' => $this->data,
            'code' => $this->getCode(),
            'file' => $this->getFile(),
            'line' => $this->getLine(),
            'preException' => $this->getPrevious(),
            'type' => get_class($this),
        );
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getData()
    {
        return $this->data;
    }

}
