<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('scheduled_time');
            $table->enum('type', ['public', 'private'])->default('public');
            $table->string('meeting_id')->unique(); // Unique meeting identifier
            $table->boolean('is_active')->default(false);
            $table->integer('participants_count')->default(0);
            $table->timestamps();
        });

        Schema::create('meeting_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('is_host')->default(false);
            $table->timestamp('joined_at')->nullable();
            $table->timestamp('left_at')->nullable();
            $table->timestamps();
            
            $table->unique(['meeting_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('meeting_participants');
        Schema::dropIfExists('meetings');
    }
};