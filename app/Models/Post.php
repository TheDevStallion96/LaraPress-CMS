<?php

namespace App\Models;

use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int|null $author_id
 * @property string $title
 * @property string $slug
 * @property string|null $excerpt
 * @property array<int, array<string, mixed>> $content
 * @property string $type
 * @property string $status
 * @property int|null $featured_media_id
 * @property int|null $parent_id
 * @property int $menu_order
 * @property string $comment_status
 * @property Carbon|null $published_at
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'author_id',
    'title',
    'slug',
    'excerpt',
    'content',
    'type',
    'status',
    'featured_media_id',
    'parent_id',
    'menu_order',
    'comment_status',
    'published_at',
])]
class Post extends Model
{
    /** @use HasFactory<PostFactory> */
    use HasFactory, SoftDeletes;

    /**
     * The attributes that should be set to their default values.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'type' => 'post',
        'status' => 'draft',
        'comment_status' => 'open',
        'content' => '[]',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'content' => 'array',
            'menu_order' => 'integer',
            'published_at' => 'datetime',
        ];
    }

    /**
     * The user that authored this post.
     *
     * @return BelongsTo<User, $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * The featured media attached to this post.
     *
     * @return BelongsTo<Media, $this>
     */
    public function featuredMedia(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'featured_media_id');
    }

    /**
     * The parent post of this post.
     *
     * @return BelongsTo<Post, $this>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'parent_id');
    }

    /**
     * The child posts of this post.
     *
     * @return HasMany<Post, $this>
     */
    public function children(): HasMany
    {
        return $this->hasMany(Post::class, 'parent_id');
    }

    /**
     * The meta entries attached to this post.
     *
     * @return HasMany<PostMeta, $this>
     */
    public function meta(): HasMany
    {
        return $this->hasMany(PostMeta::class, 'post_id');
    }

    /**
     * The revisions of this post.
     *
     * @return HasMany<Revision, $this>
     */
    public function revisions(): HasMany
    {
        return $this->hasMany(Revision::class, 'post_id');
    }

    /**
     * The terms assigned to this post.
     *
     * @return BelongsToMany<Term, $this>
     */
    public function terms(): BelongsToMany
    {
        return $this->belongsToMany(Term::class, 'post_term')
            ->withPivot('term_order')
            ->withTimestamps();
    }

    /**
     * The comments attached to this post.
     *
     * @return HasMany<Comment, $this>
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'post_id');
    }

    /**
     * Scope to published posts.
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopePublished($query): Builder
    {
        return $query->where('status', 'published');
    }
}
