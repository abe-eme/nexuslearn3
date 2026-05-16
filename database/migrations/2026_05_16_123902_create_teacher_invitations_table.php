<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('teacher_invitations', function (Blueprint $table) {
            $table->id();
            
            // The targeted teacher's email address
            $table->string('email')->unique();
            
            // The unique secure registration token
            $table->string('token')->unique();
            
            // Expiration timestamp (e.g., links expire after 48 hours)
            $table->timestamp('expires_at');
            
            // Tracks if the link has already been clicked and claimed
            $table->boolean('is_used')->default(false);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_invitations');
    }
};