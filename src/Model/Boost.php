<?php
namespace T8891\LineBoost\Model;

use Illuminate\Database\Eloquent\Model;
use T8891\LineBoost\Scopes\Compaign;

class Boost extends Model
{  
    protected $table = null;

    public $timestamps = false;

    public function __construct()
    {
        $this->setTable(config('boost.table.boost_list'));

        $this->fillable(['unique_id', 'compaign', 'auth_id', 'is_del']);

        parent::__construct();
    }

    /**
     * 模型的「启动」方法
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new Compaign);
    }

    /**
     * 關聯 User Auth
     *
     * @return void
     */
    public function auth()
    {
        return $this->belongsTo('T8891\LineBoost\Model\UserAuth', 'auth_id', 'id');
    }
}