<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('version_id')->constrained('versions')->onDelete('cascade');

            // --- Kolom yang disalin persis dari tabel 'employees' ---
            $table->bigInteger('NIK');
            $table->string('Nama');
            $table->string('GENDER');
            $table->string('UNIT');
            $table->string('JABATAN')->nullable();
            $table->string('KELOMPOK_KELAS_JABATAN')->nullable();
            $table->integer('GRADE')->nullable();
            $table->string('STATUS_KEPEGAWAIAN');
            $table->integer('USIA');
            $table->string('PENDIDIKAN');
            $table->integer('MASA_KERJA');
            // ---------------------------------------------------------
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_history');
    }
};