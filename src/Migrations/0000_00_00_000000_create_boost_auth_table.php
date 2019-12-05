<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoostAuthTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable(config('boost.table.user_auth'))) {
            Schema::create(config('boost.table.user_auth'), function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->charset = 'utf8';
                $table->collation = 'utf8_unicode_ci';
                $table->increments('id');
                $table->string('compaign', 16);
                $table->string('line_id', 64);
                $table->string('name', 32);
                $table->string('headpic', 256);
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
