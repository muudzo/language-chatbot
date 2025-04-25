<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhrasesTable extends Migration
{
    public function up()
    {
        Schema::create('phrases', function (Blueprint $table) {
            $table->id();
            $table->string('english');
            $table->text('native');
            $table->string('language'); // 'shona' or 'ndebele'
            $table->string('context')->nullable();
            $table->timestamps();
            
            // Add index for faster lookups
            $table->index(['english', 'language']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('phrases');
    }
}