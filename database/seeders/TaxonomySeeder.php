<?php

namespace Database\Seeders;

use App\Models\Taxonomy;
use App\Models\Term;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TaxonomySeeder extends Seeder
{
    /**
     * Seed the default taxonomies and a few starter terms.
     */
    public function run(): void
    {
        $category = Taxonomy::query()->updateOrCreate(['name' => 'category']);
        $tag = Taxonomy::query()->updateOrCreate(['name' => 'tag']);

        $this->seedTerms($category->id, ['Uncategorized', 'News', 'Tutorials']);

        // Tags are seeded through sample content; keep a couple of generic ones.
        $this->seedTerms($tag->id, ['Laravel', 'Vue']);
    }

    /**
     * @param  array<int, string>  $names
     */
    protected function seedTerms(int $taxonomyId, array $names): void
    {
        foreach ($names as $name) {
            Term::query()->updateOrCreate(
                ['taxonomy_id' => $taxonomyId, 'slug' => Str::slug($name)],
                ['name' => $name],
            );
        }
    }
}
