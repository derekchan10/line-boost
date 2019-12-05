<?php
namespace T8891\LineBoost\Model;

use Illuminate\Database\Eloquent\Model;

class UserAuth extends Model
{
    protected $table = null;

    public $timestamps = false;

    public function __construct()
    {
        $this->setTable(config('boost.table.user_auth'));

        $this->fillable(['compaign', 'line_id', 'name', 'headpic', 'is_del']);

        parent::__construct();
    }
}