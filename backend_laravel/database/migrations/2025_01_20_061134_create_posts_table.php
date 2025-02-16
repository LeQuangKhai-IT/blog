<?php

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    use HasUuids;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('content');
            $table->binary('image_url')->nullable();
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignUuid('category_id')->constrained('categories')->onDelete('cascade');
            $table->integer('views_count')->default(0);
            $table->integer('likes_count')->default(0);
            $table->timestamps();
            $table->enum('status', ['published', 'draft', 'pending', 'scheduled', 'archived', 'private', 'trash'])->default('draft');
            $table->dateTime('published_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
