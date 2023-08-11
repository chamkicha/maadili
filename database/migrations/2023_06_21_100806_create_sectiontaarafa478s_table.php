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
        Schema::create('section_taarafa_478', function (Blueprint $table) {
            $table->id();
            $table->uuid('secure_token');
            $table->string('date_employment')->nullable();
            $table->string('type_employment')->nullable();
            $table->string('salary')->nullable();
            $table->string('posh')->nullable();
            $table->string('other_revenue')->nullable();
            $table->string('last_title')->nullable();
            $table->string('last_date_employment')->nullable();
            $table->string('last_title_date')->nullable();
            $table->string('last_end_title_date')->nullable();
            $table->string('last_end_title_date')->nullable();
            $table->longText('risk_description_en')->nullable();
            $table->foreignIdFor(Staff::class,'staff_id')->nullable()->index()->constrained()->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('section_taarafa_478');
    }
};

