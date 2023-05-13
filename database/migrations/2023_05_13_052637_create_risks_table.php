<?php

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
        Schema::create('risks', function (Blueprint $table) {
            $table->id();
            $table->uuid('secure_token');
            $table->string('risk_name_sw')->nullable();
            $table->string('risk_name_en')->nullable();
            $table->longText('risk_description_sw')->nullable();
            $table->longText('risk_description_en')->nullable();
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
        Schema::dropIfExists('risks');
    }
};
