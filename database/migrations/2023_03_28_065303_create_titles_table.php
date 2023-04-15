<?php

use App\Models\Office;
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
        Schema::create('titles', function (Blueprint $table) {
            $table->id();
            $table->uuid('secure_token');
            $table->foreignIdFor(Staff::class,'staff_id')->nullable()->index()->constrained()->onDelete('cascade');
            $table->foreignIdFor(Office::class,'office_id')->nullable()->index()->constrained()->onDelete('cascade');
            $table->string('title_en')->nullable();
            $table->string('title_sw')->nullable();
            $table->string('abbreviation')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('titles');
    }
};
