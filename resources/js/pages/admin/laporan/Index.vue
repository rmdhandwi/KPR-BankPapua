<!-- <script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Rumah, SharedData, type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { onMounted, ref, watch } from 'vue';
import { useToast } from 'vue-toast-notification';

const rumahData = ref<Rumah[]>([]);
const loading = ref(true);

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Laporan',
        href: '/developer/rumah/',
    },
];

const page = usePage<SharedData>();
const toast = useToast();

// Ambil data rumah dari props page
onMounted(() => {
    rumahData.value = page.props.rumah ?? [];
    loading.value = false;
});

watch(
    () => page.props.flash?.error,
    (message) => {
        if (message) {
            toast.error(message, { position: 'top-right', duration: 3000 });
        }
    },
    { immediate: true },
);
</script>

<template>
    <Head title="Data Rumah" />
    <AppLayout :breadcrumbs="breadcrumbItems">
        <HeadingSmall title="Data Rumah" description="Daftar rumah KPR" />
    </AppLayout>
</template> -->
