<?php

use App\Models\Country;
use App\Models\Family_member;
use App\Models\Region;
use App\Models\Source_of_income;
use App\Models\Type_of_use;
use App\Models\User_declaration;
use App\Models\Ward;
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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->uuid('secure_token');
            $table->foreignIdFor(User_declaration::class,'user_declaration_id')->index()->constrained()->onDelete('cascade');
            $table->foreignIdFor(Family_member::class,'family_member_id')->nullable()->index()->constrained()->onDelete('cascade');
            $table->float('size_of_the_area')->nullable();
            $table->float('value_or_costs_of_construction_or_purchase')->nullable();
            $table->foreignIdFor(Source_of_income::class,'source_of_income_id')->nullable()->index()->constrained()->onDelete('cascade');
            $table->foreignIdFor(Country::class,'country_id')->nullable()->index()->constrained()->onDelete('cascade');
            $table->foreignIdFor(Region::class,'region_id')->nullable()->index()->constrained()->onDelete('cascade');
            $table->foreignIdFor(Ward::class,'ward_id')->nullable()->index()->constrained()->onDelete('cascade');
            $table->foreignIdFor(Type_of_use::class,'type_of_use_id')->nullable()->index()->constrained()->onDelete('cascade');
            $table->string('street')->nullable();
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
        Schema::dropIfExists('properties');
    }
};
