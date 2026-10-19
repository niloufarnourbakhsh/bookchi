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
        Schema::create('contributors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('bio')->nullable();
            $table->timestamps();
        });
        Schema::create('book_contributor', function (Blueprint $table) {
            $table->foreignId("book_id")->constrained('books')->cascadeOnDelete();
            $table->foreignId("contributor_id")->constrained('contributors')->cascadeOnDelete();
            $table->string('role');
            $table->primary(['book_id','contributor_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_contributor');
        Schema::dropIfExists('contributors');
    }
};
