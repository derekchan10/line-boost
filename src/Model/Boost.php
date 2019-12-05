<?php
namespace T8891\LineBoost\Model;

use Illuminate\Database\Eloquent\Model;

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
}