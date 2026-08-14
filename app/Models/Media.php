<?php

namespace App\Models;

use Database\Factories\MediaFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int|null $user_id
 * @property string $filename
 * @property string $path
 * @property string $disk
 * @property string|null $mime_type
 * @property int|null $width
 * @property int|null $height
 * @property int $size
 * @property string|null $alt
 * @property string|null $caption
 * @property string|null $title
 * @property string|null $description
 * @property array<int, array<string, mixed>> $conversions
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'user_id',
    'filename',
    'path',
    'disk',
    'mime_type',
    'width',
    'height',
    'size',
    'alt',
    'caption',
    'title',
    'description',
    'conversions',
])]
class Media extends Model
{
    /** @use HasFactory<MediaFactory> */
    use HasFactory;

    /**
     * The attributes that should be set to their default values.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'conversions' => '[]',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'width' => 'integer',
            'height' => 'integer',
            'size' => 'integer',
            'conversions' => 'array',
        ];
    }

    /**
     * The user that uploaded this media.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The posts that use this media as their featured image.
     *
     * @return HasMany<Post, $this>
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'featured_media_id');
    }

    /**
     * The URL for the stored file.
     */
    public function getUrlAttribute(): string
    {
        return url('storage/'.$this->path);
    }
}
