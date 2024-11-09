<?php

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
        Schema::create('sitemap_exclusions', function (Blueprint $table) {
            $table->id();
            $table->string('resource_type'); // مثال: "article", "page"
            $table->unsignedBigInteger('resource_id'); // ID للمورد
            $table->boolean('is_included')->default(true); // لتحديد ما إذا كان يجب تضمينه
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sitemap_exclusions');
    }
};
