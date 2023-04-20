<?php

use App\Models\Complainant_type;
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
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->uuid('secure_token');
            $table->longText('compliant');
            $table->unsignedBigInteger('respondent')->index();
            $table->foreign('respondent')->references('id')->on('users')->onDelete('cascade');
            $table->foreignIdFor(Complainant_type::class,'complainant_type')->index()->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('complaints');
    }
};
