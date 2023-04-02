<?php

use App\Models\Family_member;
use App\Models\Type_of_use;
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
        Schema::create('bank_informations', function (Blueprint $table) {
            $table->id();
            $table->string('secure_token');
            $table->foreignIdFor(User_declaration::class,'user_declaration_id')->index()->constrained()->onDelete('cascade');
            $table->foreignIdFor(Family_member::class,'family_member_id')->nullable()->index()->constrained()->onDelete('cascade');
            $table->string('institute_name')->nullable();
            $table->string('account_number')->nullable();
            $table->float('amount',30,2);
            $table->string('source')->nullable();
            $table->float('profit',30,2)->nullable();
            $table->boolean('is_local')->default(true);
            $table->foreignIdFor(Type_of_use::class,'type_of_use_id')->index()->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('bank_informations');
    }
};
