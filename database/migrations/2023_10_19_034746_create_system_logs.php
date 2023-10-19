<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_logs', function (Blueprint $table) {
            $table->id();
            $table->string('user_id', 20)->nullable()->comment('用户ID');
            $table->string('username', 50)->nullable()->comment('用户名');
            $table->string('geoip')->nullable()->comment('IP');
            $table->string('uri')->nullable()->comment('URL');
            $table->string('method')->nullable()->comment('请求方式');
            $table->text('request')->nullable()->comment('请求参数');
            $table->text('response')->nullable()->comment('响应参数');
            $table->string('user_agent')->nullable()->comment('User Agent');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_logs');
    }
};
