<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import SiteNav from '@/components/SiteNav.vue';
import { dashboard } from '@/routes';
import type { SiteMenuItem } from '@/types';

type SiteSettings = {
    title: string;
    tagline: string;
    posts_per_page: number;
};

type PageProps = {
    site: SiteSettings;
    menus: Record<string, SiteMenuItem[]>;
};

const page = usePage<PageProps>();

const site = computed(() => page.props.site);
const headerMenu = computed(() => page.props.menus.header ?? []);
const footerMenu = computed(() => page.props.menus.footer ?? []);
</script>

<template>
    <Head>
        <title>{{ site.title }}</title>
        <meta name="description" :content="site.tagline" />
    </Head>

    <div class="flex min-h-screen flex-col bg-background text-foreground">
        <header class="border-b border-border">
            <div class="mx-auto flex h-16 w-full max-w-5xl items-center justify-between gap-4 px-6">
                <Link href="/" class="flex items-center gap-2 text-lg font-semibold">
                    {{ site.title }}
                </Link>

                <nav class="hidden items-center gap-2 md:flex">
                    <SiteNav
                        v-if="headerMenu.length > 0"
                        :items="headerMenu"
                        class="flex items-center gap-1"
                    />
                    <template v-if="$page.props.auth.user">
                        <Link
                            :href="dashboard()"
                            class="rounded-md px-3 py-2 text-sm font-medium hover:bg-accent hover:text-accent-foreground"
                        >
                            Admin
                        </Link>
                    </template>
                </nav>
            </div>
        </header>

        <main class="mx-auto w-full max-w-5xl flex-1 px-6 py-10">
            <slot />
        </main>

        <footer class="border-t border-border">
            <div class="mx-auto w-full max-w-5xl px-6 py-8">
                <div class="flex flex-col justify-between gap-6 sm:flex-row">
                    <div>
                        <p class="font-semibold">{{ site.title }}</p>
                        <p class="text-sm text-muted-foreground">{{ site.tagline }}</p>
                    </div>
                    <SiteNav
                        v-if="footerMenu.length > 0"
                        :items="footerMenu"
                        class="flex flex-col gap-1 sm:items-end"
                    />
                </div>
                <p class="mt-6 text-xs text-muted-foreground">
                    Powered by LaraPress.
                </p>
            </div>
        </footer>
    </div>
</template>
