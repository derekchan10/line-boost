<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable(config('boost.table.boost'))) {
            Schema::create(config('boost.table.boost'), function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->charset = 'utf8';
                $table->collation = 'utf8_unicode_ci';
                $table->increments('id');
                $table->string('compaign', 16);
                $table->string('unique_id', 16);
                $table->integer('line_id');
                $table->tinyInteger('is_del');
                $table->timestamp('add_time')->useCurrent();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
