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
        Schema::create('legal_cases', function (Blueprint $table) {
            $table->id();
            $table->string('case_number')->nullable();
            $table->string('clarification')->nullable();
            $table->date('trial_date')->nullable();
            $table->string('mediator')->nullable();
            $table->text('notes')->nullable();
            $table->text('description')->nullable();
            $table->text('file_sk')->nullable();
            $table->text('file_suit')->nullable();
            $table->text('file_proof')->nullable();
            $table->unsignedBigInteger('created_by')->default(1);
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->index(['created_by','updated_by','deleted_by'], 'case_defendants_IDX');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('legal_cases');
    }
};
