<?php
namespace T8891\LineBoost\Exception;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class LineBoostExceptionHandler extends ExceptionHandler
{

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if (config('boost.custom_exception_handle') == 0 && $this->isLineBoostException($exception)) {
            return $this->renderLineBoostException($exception);
        }

        return parent::render($request, $exception);
    }

    /**
     * Render an exception into an Json response.
     *
     * @param \Exception $e
     * @return boolean
     */
    public function isLineBoostException(Exception $e)
    {
        return $e instanceof LineBoostException;
    }

    /**
     * Render the given JsonException.
     *
     * @param  JsonException  $e
     * @return Json
     */
    protected function renderLineBoostException(LineBoostException $e)
    {
        $content = $e->getMessage();
        $code = $e->getCode();

        return ['status' => $code, 'data' => ['content' => $content]];
    }
}