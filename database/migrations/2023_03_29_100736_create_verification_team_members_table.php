<?php

use App\Models\Staff;
use App\Models\Verification_team;
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
        Schema::create('verification_team_members', function (Blueprint $table) {
            $table->id();
            $table->uuid('secure_token');
            $table->foreignIdFor(Verification_team::class,'verification_team_id')->index()->constrained()->onDelete('cascade');
            $table->foreignIdFor(Staff::class,'staff_id')->index()->constrained()->onDelete('cascade');
            $table->boolean('is_leader')->default(false);
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
        Schema::dropIfExists('verification_team_members');
    }
};
