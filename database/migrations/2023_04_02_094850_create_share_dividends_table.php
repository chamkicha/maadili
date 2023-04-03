<?php

use App\Models\Country;
use App\Models\District;
use App\Models\Family_member;
use App\Models\Region;
use App\Models\User_declaration;
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
        Schema::create('share_dividends', function (Blueprint $table) {
            $table->id();
            $table->uuid('secure_token');
            $table->foreignIdFor(User_declaration::class,'user_declaration_id')->index()->constrained()->onDelete('cascade');
            $table->foreignIdFor(Family_member::class,'family_member_id')->nullable()->index()->constrained()->onDelete('cascade');
            $table->string('amount_of_stock');
            $table->string('institute_name');
            $table->foreignIdFor(Country::class,'country_id')->index()->constrained()->onDelete('cascade');
            $table->foreignIdFor(Region::class,'region_id')->index()->constrained()->onDelete('cascade');
            $table->foreignIdFor(District::class,'district_id')->nullable()->index()->constrained()->onDelete('cascade');
            $table->float('amount_of_dividend')->nullable();
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
        Schema::dropIfExists('share_dividends');
    }
};
