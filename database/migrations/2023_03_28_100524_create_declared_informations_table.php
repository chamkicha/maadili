<?php

use App\Models\Requirement;
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
        Schema::create('declared_informations', function (Blueprint $table) {
            $table->id();
            $table->string('secure_token');
            $table->foreignIdFor(User_declaration::class,'user_declaration_id')->index()->constrained()->onDelete('cascade');
            $table->foreignIdFor(Requirement::class,'requirement_id')->index()->constrained()->onDelete('cascade');
            $table->string('value');
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
        Schema::dropIfExists('declared_informations');
    }
};
