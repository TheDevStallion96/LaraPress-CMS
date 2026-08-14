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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->text('excerpt')->nullable();
            $table->json('content')->default('[]');
            $table->string('type')->default('post')->index();
            $table->string('status')->default('draft')->index();
            $table->foreignId('featured_media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('posts')->nullOnDelete();
            $table->unsignedInteger('menu_order')->default(0);
            $table->string('comment_status')->default('open');
            $table->timestamp('published_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->unique(['type', 'slug']);
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
