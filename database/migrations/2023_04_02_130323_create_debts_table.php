<?php

use App\Models\Debt_type;
use App\Models\Family_member;
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
        Schema::create('debts', function (Blueprint $table) {
            $table->id();
            $table->uuid('secure_token');
            $table->foreignIdFor(User_declaration::class,'user_declaration_id')->index()->constrained()->onDelete('cascade');
            $table->foreignIdFor(Family_member::class,'family_member_id')->nullable()->index()->constrained()->onDelete('cascade');
            $table->foreignIdFor(Debt_type::class,'debt_type_id')->nullable()->index()->constrained()->onDelete('cascade');
            $table->string('institute')->nullable();
            $table->float('amount',30,2)->nullable();
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
        Schema::dropIfExists('debts');
    }
};
