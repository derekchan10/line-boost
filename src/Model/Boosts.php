<?php
namespace T8891\LineBoost\Model;

use Illuminate\Database\Eloquent\Model;

class BoostList extends Model
{  
    protected $table = null;
    public $timestamps = false;

    public function __construct()
    {
        $this->setTable(config('boost.table.boost_list'));

        parent::__construct();
    }
}