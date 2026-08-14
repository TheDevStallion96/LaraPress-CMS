<?php

namespace App\Models;

use Database\Factories\TermFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $taxonomy_id
 * @property string $name
 * @property string $slug
 * @property int|null $parent_id
 * @property string|null $description
 * @property int $count
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['taxonomy_id', 'name', 'slug', 'parent_id', 'description', 'count'])]
class Term extends Model
{
    /** @use HasFactory<TermFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'count' => 'integer',
        ];
    }

    /**
     * The taxonomy this term belongs to.
     *
     * @return BelongsTo<Taxonomy, $this>
     */
    public function taxonomy(): BelongsTo
    {
        return $this->belongsTo(Taxonomy::class);
    }

    /**
     * The parent term of this term.
     *
     * @return BelongsTo<Term, $this>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Term::class, 'parent_id');
    }

    /**
     * The child terms of this term.
     *
     * @return HasMany<Term, $this>
     */
    public function children(): HasMany
    {
        return $this->hasMany(Term::class, 'parent_id');
    }

    /**
     * The posts assigned to this term.
     *
     * @return BelongsToMany<Post, $this>
     */
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_term')
            ->withPivot('term_order')
            ->withTimestamps();
    }
}
