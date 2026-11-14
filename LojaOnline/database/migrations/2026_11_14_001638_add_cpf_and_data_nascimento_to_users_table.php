<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Armazena os 11 dígitos limpos do CPF sem repetição no banco
            $table->string('cpf', 11)->unique()->nullable()->after('email');
            $table->date('data_nascimento')->nullable()->after('cpf');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['cpf', 'data_nascimento']);
        });
    }
};