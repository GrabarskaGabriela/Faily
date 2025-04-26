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
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->integer('age')->nullable();
            $table->string('phone')->nullable();
            $table->text('description')->nullable();
            $table->string('photo_path')->nullable();
            $table->string('language')->default('pl');
            $table->string('theme')->default('dark');
            $table->timestamp('last_login_at')->nullable();

            #może się kiedyś przyda
            $table->boolean('two_factor_enabled')->default(false)->nullable();
            $table->boolean('email_notifications')->default(true)->nullable();
            $table->timestamp('photo_updated_at')->nullable();
            $table->timestamp('password_updated_at')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'last_name',
                'age',
                'phone',
                'description',
                'photo_path',
                'language',
                'theme',
                'last_login_at',
                'two_factor_enabled',
                'email_notifications',
                'photo_updated_at',
                'password_updated_at',
            ]);
        });
    }
};
