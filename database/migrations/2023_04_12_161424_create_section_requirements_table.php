<?php

use App\Models\Requirement;
use App\Models\Section_requirement;
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
        Schema::create('section_requirements', function (Blueprint $table) {
            $table->id();
            $table->uuid('secure_token');
            $table->foreignIdFor(Section_requirement::class,'section_requirement_id')->index()->constrained()->onDelete('cascade');
            $table->foreignIdFor(Requirement::class,'requirement_id')->index()->constrained()->onDelete('cascade');
            $table->string('table_name')->nullable();
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
        Schema::dropIfExists('section_requirements');
    }
};
