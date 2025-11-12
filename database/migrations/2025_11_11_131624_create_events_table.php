<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->date('event_date');
            $table->time('event_time');
            $table->string('location');
            $table->integer('max_attendees')->nullable();
            $table->foreignId('created_by')->constrained('admins');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('event_attendees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['event_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('event_attendees');
        Schema::dropIfExists('events');
    }
};