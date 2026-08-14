<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { FileText, Image, MessageSquare, Users } from '@lucide/vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { dashboard } from '@/routes';

type DashboardStats = {
    posts: {
        total: number;
        published: number;
        draft: number;
        pending: number;
        future: number;
        trash: number;
    };
    pages: number;
    comments: {
        total: number;
        pending: number;
    };
    users: number;
};

type RecentPost = {
    id: number;
    title: string;
    slug: string;
    status: string;
    author: { id: number; name: string } | null;
    created_at: string;
};

type RecentComment = {
    id: number;
    author_name: string | null;
    content: string;
    status: string;
    created_at: string;
    post: { id: number; title: string; slug: string } | null;
};

const props = defineProps<{
    stats: DashboardStats;
    recentComments: RecentComment[];
    recentPosts: RecentPost[];
    versions: { laravel: string; php: string; cms: string };
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: dashboard(),
            },
        ],
    },
});

const statusLabels: Record<string, string> = {
    published: 'Published',
    draft: 'Draft',
    pending: 'Pending',
    future: 'Scheduled',
    private: 'Private',
    trash: 'Trash',
};

const statusClasses: Record<string, string> = {
    published: 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300',
    draft: 'bg-neutral-100 text-neutral-700 dark:bg-neutral-800 dark:text-neutral-300',
    pending: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300',
    future: 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300',
    private: 'bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300',
    trash: 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300',
};

const statCards = [
    {
        label: 'Posts',
        value: props.stats.posts.total,
        hint: `${props.stats.posts.published} published`,
        icon: FileText,
    },
    {
        label: 'Pages',
        value: props.stats.pages,
        hint: 'total pages',
        icon: Image,
    },
    {
        label: 'Comments',
        value: props.stats.comments.total,
        hint: `${props.stats.comments.pending} pending`,
        icon: MessageSquare,
    },
    {
        label: 'Users',
        value: props.stats.users,
        hint: 'registered users',
        icon: Users,
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <div class="flex flex-col gap-6 p-6">
        <div>
            <h1 class="text-xl font-semibold tracking-tight">Dashboard</h1>
            <p class="text-sm text-muted-foreground">
                Welcome to LaraPress, version {{ versions.cms }}.
            </p>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <Card v-for="card in statCards" :key="card.label">
                <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                    <CardTitle class="text-sm font-medium">{{ card.label }}</CardTitle>
                    <component :is="card.icon" class="h-4 w-4 text-muted-foreground" />
                </CardHeader>
                <CardContent>
                    <div class="text-2xl font-bold">{{ card.value }}</div>
                    <p class="text-xs text-muted-foreground">{{ card.hint }}</p>
                </CardContent>
            </Card>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <Card>
                <CardHeader>
                    <CardTitle>Recent Posts</CardTitle>
                    <CardDescription>Your most recently updated posts.</CardDescription>
                </CardHeader>
                <CardContent class="space-y-4">
                    <div v-if="recentPosts.length === 0" class="text-sm text-muted-foreground">
                        No posts yet.
                    </div>
                    <div v-else class="space-y-4">
                        <div
                            v-for="post in recentPosts"
                            :key="post.id"
                            class="flex items-center justify-between gap-4"
                        >
                            <div class="min-w-0">
                                <Link
                                    href="#"
                                    class="truncate text-sm font-medium hover:underline"
                                >
                                    {{ post.title }}
                                </Link>
                                <p class="truncate text-xs text-muted-foreground">
                                    {{ post.author?.name ?? 'Unknown author' }}
                                </p>
                            </div>
                            <span
                                class="inline-flex shrink-0 items-center rounded-full px-2 py-0.5 text-xs font-medium"
                                :class="statusClasses[post.status] ?? statusClasses.draft"
                            >
                                {{ statusLabels[post.status] ?? post.status }}
                            </span>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <Card>
                <CardHeader>
                    <CardTitle>Recent Comments</CardTitle>
                    <CardDescription>Latest activity on your posts.</CardDescription>
                </CardHeader>
                <CardContent class="space-y-4">
                    <div v-if="recentComments.length === 0" class="text-sm text-muted-foreground">
                        No comments yet.
                    </div>
                    <div v-else class="space-y-4">
                        <div
                            v-for="comment in recentComments"
                            :key="comment.id"
                            class="flex items-start justify-between gap-4"
                        >
                            <div class="min-w-0">
                                <p class="text-sm font-medium">
                                    {{ comment.author_name ?? 'Anonymous' }}
                                </p>
                                <p class="line-clamp-2 text-sm text-muted-foreground">
                                    {{ comment.content }}
                                </p>
                                <p class="truncate text-xs text-muted-foreground">
                                    on {{ comment.post?.title ?? 'a post' }}
                                </p>
                            </div>
                            <span
                                class="inline-flex shrink-0 items-center rounded-full px-2 py-0.5 text-xs font-medium"
                                :class="statusClasses[comment.status] ?? statusClasses.pending"
                            >
                                {{ statusLabels[comment.status] ?? comment.status }}
                            </span>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>

        <Card>
            <CardHeader>
                <CardTitle>At a Glance</CardTitle>
                <CardDescription>Post breakdown by status.</CardDescription>
            </CardHeader>
            <CardContent>
                <div class="flex flex-wrap gap-2">
                    <span
                        v-for="(count, status) in stats.posts"
                        :key="status"
                        class="inline-flex items-center gap-2 rounded-full border px-3 py-1 text-xs"
                    >
                        <span class="font-medium">{{ statusLabels[status] ?? status }}</span>
                        <span class="text-muted-foreground">{{ count }}</span>
                    </span>
                </div>
                <p class="mt-4 text-xs text-muted-foreground">
                    Laravel {{ versions.laravel }} · PHP {{ versions.php }}
                </p>
            </CardContent>
        </Card>
    </div>
</template>
