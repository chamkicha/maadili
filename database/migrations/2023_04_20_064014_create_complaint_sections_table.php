<?php

use App\Models\Complaint;
use App\Models\Complaint_section;
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
        Schema::create('complaint_sections', function (Blueprint $table) {
            $table->id();
            $table->uuid('secure_token');
            $table->foreignIdFor(Complaint::class,'complaint')->index()->constrained()->onDelete('cascade');
            $table->foreignIdFor(Complaint_section::class,'complaint_section_id')->index()->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('complaint_sections');
    }
};
