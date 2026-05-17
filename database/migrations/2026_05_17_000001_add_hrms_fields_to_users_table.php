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
            if (! Schema::hasColumn('users', 'role_id')) {
                $table->unsignedBigInteger('role_id')->nullable()->after('password');
            }

            if (! Schema::hasColumn('users', 'employee_code')) {
                $table->string('employee_code')->nullable()->unique()->after('role_id');
            }

            if (! Schema::hasColumn('users', 'status')) {
                $table->string('status')->default('active')->after('employee_code');
            }

            if (! Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('status');
            }

            if (! Schema::hasColumn('users', 'avatar_color')) {
                $table->string('avatar_color', 20)->default('#2563eb')->after('phone');
            }

            if (! Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('remember_token');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = ['role_id', 'employee_code', 'status', 'phone', 'avatar_color', 'last_login_at'];

            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
