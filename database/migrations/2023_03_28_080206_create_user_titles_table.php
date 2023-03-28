<?php

use App\Models\Title;
use App\Models\User;
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
        Schema::create('user_titles', function (Blueprint $table) {
            $table->id();
            $table->string('secure_token');
            $table->foreignIdFor(User::class,'user_id')->index()->constrained()->onDelete('cascade');
            $table->foreignIdFor(Title::class,'title_id')->index()->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('user_titles');
    }
};
