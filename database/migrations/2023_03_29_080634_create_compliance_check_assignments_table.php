<?php

use App\Models\Compliance_check_type;
use App\Models\Staff;
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
        Schema::create('compliance_check_assignments', function (Blueprint $table) {
            $table->id();
            $table->uuid('secure_token');
            $table->foreignIdFor(User_declaration::class,'user_declaration_id')->index()->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('assignee_staff')->index();
            $table->unsignedBigInteger('assigned_by')->index();
            $table->foreign('assignee_staff')->references('id')->on('staff')->onDelete('cascade');
            $table->foreign('assigned_by')->references('id')->on('staff')->onDelete('cascade');
            $table->foreignIdFor(Compliance_check_type::class,'compliance_check_type_id')->index()->constrained()->onDelete('cascade');
            $table->timestamp('deadline');
            $table->longText('comment')->nullable();
            $table->timestamp('deleted_at')->nullable();
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
        Schema::dropIfExists('declaration_compliance_check_assignments');
    }
};
