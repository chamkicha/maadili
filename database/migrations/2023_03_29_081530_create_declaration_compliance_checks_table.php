<?php

use App\Models\Declaration_compliance_check_assignment;
use App\Models\Declaration_type_section;
use App\Models\Staff;
use App\Models\Status;
use App\Models\User_declaration;
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
        Schema::create('declaration_compliance_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User_declaration::class,'user_declaration_id')->index()->constrained()->onDelete('cascade');
            $table->foreignIdFor(Status::class,'status_id')->index()->constrained()->onDelete('cascade');
            $table->foreignIdFor(Staff::class,'staff_id')->index()->constrained()->onDelete('cascade');
            $table->boolean('physical_verification_needed')->default(false);
            $table->longText('comment')->nullable();
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
        Schema::dropIfExists('declaration_compliance_checks');
    }
};
