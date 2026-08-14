<script setup lang="ts">
import NavMain from '@/components/NavMain.vue';
import type { NavItem } from '@/types';

type NavSectionType = {
    label: string;
    items: NavItem[];
};

defineProps<{
    sections: NavSectionType[];
}>();
</script>

<template>
    <div class="space-y-1">
        <NavMain
            v-for="section in sections"
            :key="section.label"
            :items="section.items"
            :label="section.label"
        />
    </div>
</template>
