<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Support\Collection;

class MenuService
{
    /**
     * Resolved menu trees per location for the current request.
     *
     * @var array<string, array<int, array<string, mixed>>>
     */
    private array $cache = [];

    /**
     * Get the menu items for the given theme location as a nested tree.
     *
     * @return array<int, array<string, mixed>>
     */
    public function itemsForLocation(string $location): array
    {
        if (! array_key_exists($location, $this->cache)) {
            $menu = Menu::query()->where('location', $location)->with('items')->first();

            $this->cache[$location] = $this->buildTree($menu->items ?? collect());
        }

        return $this->cache[$location];
    }

    /**
     * Get every assigned menu grouped by location.
     *
     * @return array<string, array<int, array<string, mixed>>>
     */
    public function all(): array
    {
        $locations = [];

        foreach (Menu::pluck('location')->filter() as $location) {
            $locations[$location] = $this->itemsForLocation($location);
        }

        return $locations;
    }

    /**
     * Build a nested tree from a flat collection of menu items.
     *
     * @param  Collection<int, MenuItem>  $items
     * @return array<int, array<string, mixed>>
     */
    protected function buildTree($items): array
    {
        $tree = [];
        $byId = [];

        foreach ($items as $item) {
            $byId[$item->id] = [
                'id' => $item->id,
                'label' => $item->label,
                'url' => $item->url ?? '#',
                'children' => [],
            ];
        }

        foreach ($byId as $id => $node) {
            $parent = $items->firstWhere('id', $id)?->parent_id;

            if ($parent !== null && isset($byId[$parent])) {
                $byId[$parent]['children'][] = $node;
            } else {
                $tree[] = $node;
            }
        }

        return $tree;
    }
}
