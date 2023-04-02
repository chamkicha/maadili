<?php

use App\Models\Building_type;
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
        Schema::create('house_and_buildings', function (Blueprint $table) {
            $table->id();
            $table->string('secure_token');
            $table->foreignIdFor(User_declaration::class,'user_declaration_id')->index()->constrained()->onDelete('cascade');
            $table->foreignIdFor(Family_member::class,'family_member_id')->nullable()->index()->constrained()->onDelete('cascade');
            $table->foreignIdFor(Building_type::class,'building_type_id')->nullable()->index()->constrained()->onDelete('cascade');
            $table->foreignIdFor(Country::class,'country_id')->nullable()->index()->constrained()->onDelete('cascade');
            $table->foreignIdFor(Region::class,'region_id')->nullable()->index()->constrained()->onDelete('cascade');
            $table->foreignIdFor(Ward::class,'ward_id')->nullable()->index()->constrained()->onDelete('cascade');
            $table->string('street')->nullable();
            $table->float('area_size')->nullable();
            $table->float('value_or_costs_of_construction_or_purchase')->nullable();
            $table->foreignIdFor(Source_of_income::class,'source_of_income_id')->nullable()->index()->constrained()->onDelete('cascade');
            $table->foreignIdFor(Type_of_use::class,'type_of_use_id')->nullable()->index()->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('house_and_buildings');
    }
};
