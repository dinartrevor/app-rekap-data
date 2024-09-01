<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('case_plaintiffs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('legal_case_id');
            $table->string('name');
            $table->text('place_of_birth')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->index(['legal_case_id'], 'case_plaintiffs_IDX');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('case_plaintiffs');
    }
};
