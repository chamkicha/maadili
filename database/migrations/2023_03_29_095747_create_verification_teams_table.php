<?php

use App\Models\Financial_year;
use App\Models\Team_category;
use App\Models\Zone;
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
        Schema::create('verification_teams', function (Blueprint $table) {
            $table->id();
            $table->uuid('secure_token');
            $table->string('name');
            $table->foreignIdFor(Financial_year::class,'year_of_verification')->index();
            $table->foreign('year_of_verification')->references('id')->on('financial_years')->onDelete('cascade');
            $table->foreignIdFor(Zone::class,'zone_id')->index()->constrained()->onDelete('cascade');
            $table->string('location')->nullable();
            $table->string('description')->nullable();
            $table->timestamp('deadline');
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
        Schema::dropIfExists('verification_teams');
    }
};
