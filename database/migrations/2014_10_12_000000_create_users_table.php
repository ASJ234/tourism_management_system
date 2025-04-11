<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

public function up()
{
    Schema::create('users', function (Blueprint $table) {
        $table->id('user_id');
        $table->string('username', 50)->unique();
        $table->string('email', 100)->unique();
        $table->string('password');
        $table->string('full_name', 100)->nullable();
        $table->enum('role', ['admin', 'tour_operator', 'tourist', 'destination_manager']);
        $table->string('contact_number', 20)->nullable();
        $table->string('profile_image', 255)->nullable();
        $table->timestamp('last_login')->nullable();
        $table->boolean('is_active')->default(true);
        $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
