<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { SharedData, type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { Calculator, HomeIcon, LayoutGrid, MapPinned, NotebookText, Users } from 'lucide-vue-next';
import { computed } from 'vue';
import AppLogoIcon from './AppLogoIcon.vue';

const page = usePage<SharedData>();

const role = computed(() => page.props.auth?.user.role); // Ambil role dari backend

const mainNavItems = computed<NavItem[]>(() => {
    switch (role.value) {
        case 1: // Admin
            return [
                {
                    title: 'Dashboard',
                    href: '/admin/dashboard',
                    icon: LayoutGrid,
                },
                {
                    title: 'Data Kriteria',
                    href: '/admin/kriteria',
                    icon: NotebookText,
                    match: ['/admin/kriteria', '/admin/kriteria/*', '/admin/subkriteria', '/admin/subkriteria/*'],
                },
                {
                    title: 'Data Nasabah',
                    href: '/admin/nasabah',
                    icon: Users,
                    match: ['/admin/nasabah', '/admin/nasabah/*'],
                },
                {
                    title: 'Perhitungan',
                    href: '/admin/perhitungan',
                    icon: Calculator,
                },
                // {
                //     title: 'Perhitungan',
                //     href: '/admin/laporan',
                //     icon: MapPinned,
                // },
                {
                    title: 'Laporan',
                    href: '/admin/laporan',
                    icon: MapPinned,
                },
            ];
        case 2: // Developer
            return [
                {
                    title: 'Dashboard',
                    href: '/developer/dashboard',
                    icon: LayoutGrid,
                },
                {
                    title: 'Data Rumah',
                    href: '/developer/rumah/',
                    icon: HomeIcon,
                    match: ['/developer/rumah','/developer/rumah/create', '/developer/rumah/*'],
                },
                {
                    title: 'Data Nasabah',
                    href: '/developer/nasabah',
                    icon: Users,
                    match: ['/developer/nasabah', '/developer/nasabah/*'],
                },
            ];
        default:
            return [{ title: 'Welcome', href: '/', icon: LayoutGrid }];
    }
});

const footerNavItems: NavItem[] = [];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('admin.dashboard')">
                            <AppLogoIcon />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
