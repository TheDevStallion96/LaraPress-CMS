<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {
    BookOpen,
    ExternalLink,
    File,
    FileText,
    FolderGit2,
    Image,
    LayoutGrid,
    Menu as MenuIcon,
    MessageSquare,
    Settings as SettingsIcon,
    Users,
} from '@lucide/vue';
import AppLogo from '@/components/AppLogo.vue';
import NavFooter from '@/components/NavFooter.vue';
import NavSection from '@/components/NavSection.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard, home } from '@/routes';
import { edit as editAppearance } from '@/routes/appearance';
import { edit as editProfile } from '@/routes/profile';
import { edit as editSecurity } from '@/routes/security';
import type { NavItem } from '@/types';

const mainNavSections: { label: string; items: NavItem[] }[] = [
    {
        label: 'Dashboard',
        items: [
            {
                title: 'Dashboard',
                href: dashboard(),
                icon: LayoutGrid,
            },
        ],
    },
    {
        label: 'Content',
        items: [
            {
                title: 'Posts',
                href: '/admin/posts',
                icon: FileText,
                children: [
                    { title: 'All Posts', href: '/admin/posts' },
                    { title: 'Add New', href: '/admin/posts/create' },
                    { title: 'Categories', href: '/admin/categories' },
                    { title: 'Tags', href: '/admin/tags' },
                ],
            },
            {
                title: 'Pages',
                href: '/admin/pages',
                icon: File,
                children: [
                    { title: 'All Pages', href: '/admin/pages' },
                    { title: 'Add New', href: '/admin/pages/create' },
                ],
            },
            {
                title: 'Media',
                href: '/admin/media',
                icon: Image,
            },
            {
                title: 'Comments',
                href: '/admin/comments',
                icon: MessageSquare,
            },
        ],
    },
    {
        label: 'Management',
        items: [
            {
                title: 'Users',
                href: '/admin/users',
                icon: Users,
                children: [
                    { title: 'All Users', href: '/admin/users' },
                    { title: 'Add New', href: '/admin/users/create' },
                    { title: 'Roles', href: '/admin/roles' },
                ],
            },
            {
                title: 'Menus',
                href: '/admin/menus',
                icon: MenuIcon,
            },
            {
                title: 'Settings',
                href: editProfile(),
                icon: SettingsIcon,
                children: [
                    { title: 'Profile', href: editProfile() },
                    { title: 'Security', href: editSecurity() },
                    { title: 'Appearance', href: editAppearance() },
                ],
            },
        ],
    },
    {
        label: 'Site',
        items: [
            {
                title: 'View Site',
                href: home(),
                icon: ExternalLink,
            },
        ],
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Repository',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: FolderGit2,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavSection :sections="mainNavSections" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
