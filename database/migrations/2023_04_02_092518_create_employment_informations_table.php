<?php

use App\Models\Employment_type;
use App\Models\Office;
use App\Models\Title;
use App\Models\User;
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
        Schema::create('employment_informations', function (Blueprint $table) {
            $table->id();
            $table->uuid('secure_token');
            $table->foreignIdFor(User_declaration::class,'user_declaration_id')->index()->constrained()->onDelete('cascade');
            $table->foreignIdFor(Title::class,'title_id')->index()->constrained()->onDelete('cascade');
            $table->foreignIdFor(Office::class,'office_id')->index()->constrained()->onDelete('cascade');
            $table->foreignIdFor(Employment_type::class,'employment_type_id')->index()->constrained()->onDelete('cascade');
            $table->float('salary_per_year',10,2);
            $table->float('allowance_per_year',10,2)->nullable();
            $table->float('income_from_other_source_per_year',10,2)->nullable();
            $table->string('from',);
            $table->string('to')->nullable();
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
        Schema::dropIfExists('employment_informations');
    }
};
