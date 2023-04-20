<?php

use App\Models\Complaint;
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
        Schema::create('complaint_assignments', function (Blueprint $table) {
            $table->id();
            $table->uuid('secure_token');
            $table->foreignIdFor(Complaint::class,'complaint')->index()->constrained()->onDelete('cascade');
            $table->foreignIdFor(Staff::class,'staff_id')->index()->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('assigned_by')->index();
            $table->foreign('assigned_by')->references('id')->on('staff')->onDelete('cascade');
            $table->longText('description')->nullable();
            $table->timestamp('deadline')->nullable();
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
        Schema::dropIfExists('complaint_assignments');
    }
};
