<?php

use App\Models\Risk;
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
        Schema::table('section_requirements', function (Blueprint $table) {
            $table->boolean('is_active')->default(true);
            $table->boolean('is_mandatory')->default(true);
            $table->boolean('is_cascade')->default(false);
            $table->foreignIdFor(Section_requirement::class,'section_requirement_id')->nullable()->index()->constrained()->onDelete('cascade');
            $table->string('account')->default("neutral");
            $table->foreignIdFor(Risk::class,'risk_id')->nullable()->index()->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section_requirements', function (Blueprint $table) {
            $table->dropColumn(['is_active','is_mandatory','is_cascade','section_requirement_id','account','risk_id']);
        });
    }
};
