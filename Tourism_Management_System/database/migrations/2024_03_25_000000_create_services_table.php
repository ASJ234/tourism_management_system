<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id('service_id');
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->foreignId('destination_id')->constrained('destinations', 'destination_id')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users', 'user_id')->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('services');
    }
}; 