<?php namespace T8891\LineBoost\Middleware;

use Closure;

class BoostApiFormat
{

	/**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (isset($response->original)) {
            $original = $this->hasStatus($response->original);
            $this->tf($original,['gtm']);
            $response = response($original);
        }

        return $response;
    }

    private function hasStatus($original)
    {
        if(gettype($original) == 'string') {
            return $original;
        } elseif (!data_get($original, 'error') && !data_get($original, 'status') ) {
            $original = [
                'status' => 12000,
                'data' => $original ? : (object) []
            ];
        }
        
        return $original;
    }

    /**
     * 格式化字段（下划线转驼峰）
     * @date 2015-08-31
     */
    private function tf(&$data, $exclude = array())
    {
        if (!is_array($data) && !is_object($data)) {
            return;
        }

        $keys = array_keys($data);
        $formatKeys = array_map(function ($value) {
            $part = explode('_', $value);
            if (count($part) > 1) {
                $words = '_' . str_replace('_', " ", strtolower($value));
                $value = ltrim(str_replace(" ", "", ucwords($words)), '_');
            }
            return $value;
        }, $keys);

        $unique = array_diff($formatKeys, $keys);

        if ($unique) {
            foreach ($unique as $key => $item) {
                $data[$item] = $data[$keys[$key]];
                unset($data[$keys[$key]]);
            }
        }

        array_walk($data, function (&$item, $key) use ($exclude) {
            if (!in_array((string)$key, $exclude)) {
                $this->tf($item, $exclude);
            }
        });
    }
}
