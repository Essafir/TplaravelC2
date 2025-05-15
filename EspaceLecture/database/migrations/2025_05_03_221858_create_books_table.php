<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->text('summary')->nullable();
            $table->integer('pages');
            $table->date('published_at');
            $table->string('cover')->nullable()->comment('Chemin vers l\'image de couverture');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['available', 'borrowed'])->default('available');
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('books');
    }
};