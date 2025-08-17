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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('employee')->after('email');
            $table->string('employee_id')->nullable()->unique()->after('role');
            $table->string('department')->nullable()->after('employee_id');
            $table->string('position')->nullable()->after('department');
            $table->string('phone')->nullable()->after('position');
            $table->date('hire_date')->nullable()->after('phone');
            $table->decimal('salary', 10, 2)->nullable()->after('hire_date');
            $table->unsignedBigInteger('manager_id')->nullable()->after('salary');
            $table->enum('status', ['active', 'inactive', 'terminated'])->default('active')->after('manager_id');
            
            $table->foreign('manager_id')->references('id')->on('users');
            $table->index(['role', 'status']);
            $table->index('department');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['manager_id']);
            $table->dropIndex(['role', 'status']);
            $table->dropIndex(['department']);
            $table->dropColumn([
                'role',
                'employee_id',
                'department', 
                'position',
                'phone',
                'hire_date',
                'salary',
                'manager_id',
                'status'
            ]);
        });
    }
};