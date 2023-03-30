<?php

use App\Models\Financial_year;
use App\Models\Source;
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
        Schema::create('physical_verification_compilations', function (Blueprint $table) {
            $table->id();
            $table->string('secure_token');
            $table->foreignIdFor(User_declaration::class,'user_declaration_id')->index()->constrained()->onDelete('cascade');
            $table->foreignIdFor(Source::class,'source_id')->index()->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('year_of_verification')->index();
            $table->foreign('year_of_verification')->references('id')->on('financial_years')->onDelete('cascade');
            $table->foreignIdFor(Staff::class,'staff_id')->index()->constrained()->onDelete('cascade');
            $table->foreignIdFor(Status::class,'status_id')->index()->constrained()->onDelete('cascade');
            $table->longText('comment')->nullable();
            $table->text('remark')->nullable();
            $table->boolean('need_for_verification')->default(false);
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
        Schema::dropIfExists('physical_verification_compilations');
    }
};
