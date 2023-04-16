<?php

use App\Models\Declaration_type;
use App\Models\Financial_year;
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
        Schema::create('asset_declaration_windows', function (Blueprint $table) {
            $table->id();
            $table->uuid('secure_token');
            $table->foreignIdFor(Financial_year::class,'financial_year_id')->index()->constrained()->onDelete('cascade');
            $table->foreignIdFor(Declaration_type::class,'declaration_type_id')->index()->constrained()->onDelete('cascade');
            $table->foreignIdFor(Staff::class,'staff_id')->nullable()->index()->constrained()->onDelete('cascade');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
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
        Schema::dropIfExists('asset_declaration_windows');
    }
};
