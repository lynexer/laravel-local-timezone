<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimezoneToUsersTable extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        if (!Schema::hasColumn('users', 'timezone')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->string('timezone')->after('remember_token')->nullable();
            });
        }

        if (!Schema::hasColumn('users', 'override_timezone')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->boolean('override_timezone')->after('timezone')->default(false);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn(['timezone', 'override_timezone']);
        });
    }
}