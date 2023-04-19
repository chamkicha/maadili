<?php

use App\Models\Ward;
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
        Schema::create('research', function (Blueprint $table) {
            $table->id();
            $table->uuid('secure_token');
            $table->string('agenda');
            $table->foreignIdFor(Ward::class,'ward_id')->index()->constrained()->onDelete('cascade');
            $table->string('street')->nullable();
            $table->string('research_start_date')->nullable();
            $table->string('research_end_date')->nullable();
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
        Schema::dropIfExists('research');
    }
};
