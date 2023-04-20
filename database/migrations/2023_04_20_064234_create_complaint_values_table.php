<?php

use App\Models\Complaint;
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
        Schema::create('complaint_values', function (Blueprint $table) {
            $table->id();
            $table->uuid('secure_token');
            $table->foreignIdFor(Complaint::class,'complaint')->index()->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('complaint_values');
    }
};
