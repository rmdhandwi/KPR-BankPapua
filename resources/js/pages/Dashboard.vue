<script setup lang="ts">
import DashboardCard from '@/components/DashboardCard.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem, SharedData } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { computed, watch } from 'vue';
import { useToast } from 'vue-toast-notification';

const page = usePage<SharedData>();
const toast = useToast();

const userRole = computed(() => page.props.auth.user?.role);

const breadcrumbs = computed<BreadcrumbItem[]>(() => {
    if (userRole.value === 1) {
        return [
            {
                title: 'Dashboard Admin',
                href: '/admin/dashboard',
            },
        ];
    } else if (userRole.value === 2) {
        return [
            {
                title: 'Dashboard Developer',
                href: '/developer/dashboard',
            },
        ];
    }
    return [];
});

// Data dari backend
const rumahCount = computed(() => Number(page.props.rumahCount ?? 0));
const nasabahCount = computed(() => Number(page.props.nasabahCount ?? 0));
const kriteriaCount = computed(() => Number(page.props.kriteriaCount ?? 0));
const subKriteriaCount = computed(() => Number(page.props.subKriteriaCount ?? 0));
const nasabahLengkap = computed(() => Number(page.props.nasabahLengkap ?? 0));
const nasabahTidakLengkap = computed(() => Number(page.props.nasabahTidakLengkap ?? 0));

// Flash toast
watch(
    () => page.props.flash?.success,
    (msg) => {
        if (msg) {
            toast.success(msg, { position: 'top-right', duration: 3000 });
        }
    },
    { immediate: true },
);
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-6">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <DashboardCard
                    icon="pi pi-users"
                    :href="userRole === 1 ? route('admin.nasabah.index') : route('admin.developer.index')"
                    title="Jumlah Nasabah"
                    :value="nasabahCount"
                    bg="bg-green-100"
                    text="text-green-600"
                />

                <!-- Admin only -->
                <template v-if="userRole === 1">
                    <DashboardCard
                        :href="route('admin.kriteria.index')"
                        icon="pi pi-list"
                        title="Kriteria"
                        :value="kriteriaCount"
                        bg="bg-purple-100"
                        text="text-purple-600"
                    />
                    <DashboardCard
                        :href="route('admin.subkriteria.index')"
                        icon="pi pi-align-left"
                        title="Sub Kriteria"
                        :value="subKriteriaCount"
                        bg="bg-yellow-100"
                        text="text-yellow-600"
                    />
                    <DashboardCard
                        icon="pi pi-check-circle"
                        title="Berkas Nasabah Lengkap"
                        :value="nasabahLengkap"
                        :href="route('admin.nasabah.index')"
                        bg="bg-emerald-100"
                        text="text-emerald-600"
                    />
                    <DashboardCard
                        icon="pi pi-times-circle"
                        title="Berkas Nasabah Tidak Lengkap"
                        :value="nasabahTidakLengkap"
                        :href="route('admin.nasabah.index')"
                        bg="bg-red-100"
                        text="text-red-600"
                    />
                </template>

                <template v-if="userRole === 2">
                    <DashboardCard icon="pi pi-home" title="Jumlah Rumah" :value="rumahCount" bg="bg-blue-100" text="text-blue-600" />
                </template>
            </div>
        </div>
    </AppLayout>
</template>
