<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import type { SiteMenuItem } from '@/types';

defineProps<{
    items: SiteMenuItem[];
    class?: string;
}>();
</script>

<template>
    <ul :class="$props.class">
        <li v-for="item in items" :key="item.id">
            <Link
                :href="item.url"
                class="rounded-md px-3 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground"
            >
                {{ item.label }}
            </Link>
            <SiteNav
                v-if="item.children.length > 0"
                :items="item.children"
                class="ml-4 mt-1 space-y-1 border-l border-border pl-3"
            />
        </li>
    </ul>
</template>
