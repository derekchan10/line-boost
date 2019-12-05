<?php
namespace T8891\LineBoost\Model;

use Illuminate\Database\Eloquent\Model;

class SponsorAuth extends Model
{
    protected $table = null;

    public $timestamps = false;

    public function __construct()
    {
        $this->setTable(config('boost.table.sponsor_auth'));

        $this->fillable(['unique_id', 'compaign', 'line_id', 'name', 'headpic', 'is_del']);

        parent::__construct();
    }
}