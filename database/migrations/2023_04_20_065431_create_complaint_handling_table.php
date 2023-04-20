<?php

use App\Models\Complaint_assignment;
use App\Models\Status;
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
        Schema::create('complaint_handling', function (Blueprint $table) {
            $table->id();
            $table->uuid('secure_token');
            $table->foreignIdFor(Complaint_assignment::class,'complaint_assignment_id')->index()->constrained()->onDelete('cascade');
            $table->boolean('is_preliminary_review')->default(false);
            $table->boolean('is_details_letter_sent')->default(false);
            $table->boolean('is_defense_details_submitted')->default(false);
            $table->boolean('is_concern_law')->default(false);
            $table->boolean('is_physical_verification_required')->default(false);
            $table->longText('comment_against_law_issues')->nullable();
            $table->longText('comment_for_verification_investigation')->nullable();
            $table->string('investigation_report')->nullable();
            $table->boolean('complaint_not_confirmed')->default(false);
            $table->boolean('is_notifications_sent')->default(false);
            $table->boolean('is_ethic_cabinet_required')->default(false);
            $table->boolean('cabinet_report_submitted_to_commissioner')->default(false);
            $table->boolean('cabinet_report_submitted_to_speaker')->default(false);
            $table->string('other_institution_name_complaint')->default(false);
            $table->longText('other_comment')->nullable();
            $table->foreignIdFor(Status::class,'status_id')->index()->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('complaint_handling');
    }
};
