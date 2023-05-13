<?php

use App\Models\Risk;
use App\Models\Staff;
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
        Schema::create('risk_conditions', function (Blueprint $table) {
            $table->id();
            $table->uuid('secure_token');
            $table->string('min_condition')->nullable();
            $table->string('condition_operator')->nullable();
            $table->string('max_condition')->nullable();
            $table->foreignIdFor(Risk::class,'risk_id')->index()->constrained()->onDelete('cascade');
            $table->foreignIdFor(Staff::class,'staff_id')->nullable()->index()->constrained()->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
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
        Schema::dropIfExists('risk_conditions');
    }
};
