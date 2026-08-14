<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';

type PublicPost = {
    id: number;
    title: string;
    slug: string;
    excerpt: string | null;
    published_at: string | null;
    author: { id: number; name: string } | null;
};

type PageProps = {
    site: { title: string; tagline: string };
};

const page = usePage<PageProps>();

defineProps<{
    posts: PublicPost[];
}>();
</script>

<template>
    <Head title="Home" />

    <div class="flex flex-col gap-10">
        <section class="space-y-2">
            <h1 class="text-3xl font-bold tracking-tight">{{ page.props.site.title }}</h1>
            <p v-if="page.props.site.tagline" class="text-muted-foreground">
                {{ page.props.site.tagline }}
            </p>
        </section>

        <section class="flex flex-col gap-6">
            <h2 class="text-xl font-semibold">Latest Posts</h2>

            <p v-if="posts.length === 0" class="text-sm text-muted-foreground">
                No posts yet. Check back soon.
            </p>

            <article
                v-for="post in posts"
                :key="post.id"
                class="rounded-lg border border-border p-6"
            >
                <h3 class="text-lg font-semibold">{{ post.title }}</h3>
                <p class="mt-1 text-sm text-muted-foreground">{{ post.excerpt }}</p>
                <p class="mt-2 text-xs text-muted-foreground">
                    {{ post.author?.name ?? 'Unknown author' }}
                </p>
            </article>
        </section>
    </div>
</template>
