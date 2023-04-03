<?php

use App\Models\Declaration_type;
use App\Models\Financial_year;
use App\Models\User;
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
        Schema::create('user_declarations', function (Blueprint $table) {
            $table->id();
            $table->uuid('secure_token');
            $table->foreignIdFor(User::class,'user_id')->index()->constrained()->onDelete('cascade');
            $table->foreignIdFor(Declaration_type::class,'declaration_type_id')->index()->constrained()->onDelete('cascade');
            $table->string('adf_number')->unique()->nullable();
            $table->foreignIdFor(Financial_year::class,'financial_year_id')->index()->constrained()->onDelete('cascade');
            $table->string('flag');
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
        Schema::dropIfExists('user_declarations');
    }
};
