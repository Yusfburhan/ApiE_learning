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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subcategory_id');
            $table->integer('teacherid');
            $table->string('title')->default('no title');
            $table->text('description')->default('');
            $table->text('imageofcourse');
            $table->integer('duration')->default(0);
            $table->string('instructor');
            $table->decimal('price', 10, 2)->nullable()->default(0);
            $table->string('currency')->default('IQD');
            $table->integer('rating');
            $table->foreign('subcategory_id')
            ->references('id')
            ->on('subcategories')
            ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
