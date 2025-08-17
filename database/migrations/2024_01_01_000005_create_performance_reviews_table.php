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
        Schema::create('performance_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('reviewer_id')->constrained('users');
            $table->date('review_period_start');
            $table->date('review_period_end');
            $table->integer('performance_score')->comment('1-10 scale');
            $table->text('goals_achieved')->nullable();
            $table->text('areas_for_improvement')->nullable();
            $table->text('manager_comments')->nullable();
            $table->text('employee_comments')->nullable();
            $table->enum('status', ['draft', 'completed', 'acknowledged'])->default('draft');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            $table->index(['employee_id', 'review_period_start']);
            $table->index('reviewer_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_reviews');
    }
};