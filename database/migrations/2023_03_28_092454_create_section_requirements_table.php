<?php

use App\Models\Declaration_type_section;
use App\Models\Requirement;
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
            $table->foreignIdFor(Declaration_type_section::class,'declaration_type_section_id')->index()->constrained()->onDelete('cascade');
            $table->foreignIdFor(Requirement::class,'requirement_id')->index()->constrained()->onDelete('cascade');
            $table->boolean('is_required')->default(false);
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
