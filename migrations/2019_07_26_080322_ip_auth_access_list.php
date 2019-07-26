<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IpAuthAccessList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ip_auth_access_list', function($table) {
            $table->increments('id');
            $table->string('label');
            $table->string('list');
            $table->string('type');
            $table->bigInteger('range_start')->unsigned();
            $table->bigInteger('range_end')->unsigned();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['list', 'type', 'range_start', 'range_end'], 'range_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ip_auth_access_list');
    }
}
