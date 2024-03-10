<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('adm')->default(false);
            $table->boolean('status')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });

        DB::table('users')->insert(
            [
                [
                    'name' => "Danillo Rodrigues",
                    'email' => "dandrn7@gmail.com",
                    'password' => Hash::make('1234567890'),
                    'adm' => true,
                ],
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
