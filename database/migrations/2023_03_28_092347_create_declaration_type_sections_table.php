<?php

use App\Models\Declaration_type;
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
        Schema::create('declaration_type_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor( Declaration_type::class,'declaration_type_id')->index()->constrained()->onDelete('cascade');
            $table->string('section_title');
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
        Schema::dropIfExists('declaration_type_sections');
    }
};
