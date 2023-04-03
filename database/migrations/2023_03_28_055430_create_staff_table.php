<?php

use App\Models\Role;
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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->uuid('secure_token');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone_number');
            $table->string('email')->unique();
            $table->string('otp')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('current_login')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->string('token')->nullable();
            $table->foreignIdFor(Role::class,'role_id')->index()->constrained()->onDelete('cascade');
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('staff');
    }
};
