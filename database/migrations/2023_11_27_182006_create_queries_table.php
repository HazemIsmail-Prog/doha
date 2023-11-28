<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('queries', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class,'bidder_id')->nullable();
            $table->foreignIdFor(User::class,'consultant_id')->nullable();
            $table->string('discipline')->nullable();
            $table->string('particulars')->nullable();
            $table->string('volume_number')->nullable();
            $table->string('page_clause_number')->nullable();
            $table->longText('description')->nullable();
            $table->longText('clarification')->nullable();
            $table->longText('reply')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queries');
    }
};
