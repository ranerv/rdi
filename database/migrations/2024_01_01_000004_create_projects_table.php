<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lead_proponent_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('project_code')->unique()->nullable();
            $table->enum('type', ['research', 'extension'])->default('research');
            $table->enum('status', ['pending', 'ongoing', 'completed', 'cancelled'])->default('pending');
            $table->enum('funding_type', ['internal', 'external', 'cofunded'])->default('internal');
            $table->decimal('approved_budget', 15, 2)->default(0);
            $table->longText('objectives')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('location_description')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['type', 'status', 'funding_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
