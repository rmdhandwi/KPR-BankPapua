<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Rumah, SharedData, type BreadcrumbItem } from '@/types';
import { Head, router, usePage } from '@inertiajs/vue3';
import { FilterMatchMode } from '@primevue/core/api';
import { ConfirmDialog, useConfirm } from 'primevue';
import { onMounted, ref, watch } from 'vue';
import { useToast } from 'vue-toast-notification';

const rumahData = ref<Rumah[]>([]);
const loading = ref(true);

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Data Rumah',
        href: '/developer/rumah/',
    },
];

const page = usePage<SharedData>();
const toast = useToast();
const confirm = useConfirm();

function goToEdit(id: number) {
    router.post(route('developer.rumah.edit.init', { id }));
}

function confirmDelete(id: number) {
    confirm.require({
        message: 'Apakah Anda yakin ingin menghapus data rumah ini?',
        header: 'Konfirmasi Hapus',
        icon: 'pi pi-exclamation-triangle',
        rejectProps: {
            label: 'Batal',
            severity: 'secondary',
            outlined: true,
        },
        acceptProps: {
            label: 'Ya',
        },
        accept: () => doDestroy(id),
        reject: () => {
            toast.info('Batal menghapus data', { position: 'top-right' });
        },
    });
}

function formatRupiah(angka: number | string): string {
    const number = typeof angka === 'string' ? parseInt(angka) : angka;
    return number.toLocaleString('id-ID');
}

function doDestroy(id: number) {
    router.delete(route('developer.rumah.destroy', id), {
        onSuccess: () => {
            const message = page.props.flash?.success;
            if (message) {
                toast.success(message, { position: 'top-right' });
            }
            router.visit(route('developer.rumah.index'));
        },
        onError: () => {
            const message = page.props.flash?.error ?? 'Gagal menghapus data.';
            toast.error(message, { position: 'top-right' });
        },
    });
}

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

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
    <ConfirmDialog />
    <AppLayout :breadcrumbs="breadcrumbItems">
        <HeadingSmall title="Data Rumah" description="Daftar rumah KPR" />
        <Card>
            <template #content>
                <DataTable
                    :filters="filters"
                    :value="rumahData"
                    :loading="loading"
                    dataKey="id_rumah"
                    responsiveLayout="scroll"
                    size="small"
                    paginator
                    paginatorTemplate="RowsPerPageDropdown FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink"
                    currentPageReportTemplate="{first} ke {last} dari {totalRecords}"
                    :rows="10"
                    scrollable
                    scrollHeight="500px"
                    :rowsPerPageOptions="[5, 10, 20, 50, 100]"
                >
                    <template #header>
                        <div class="mb-3 grid gap-2 md:grid-cols-1 lg:grid-cols-2">
                            <div class="flex">
                                <Button
                                    as="a"
                                    :href="route('developer.rumah.create')"
                                    severity="info"
                                    size="small"
                                    icon="pi pi-plus"
                                    label="Tambah data"
                                />
                            </div>
                            <IconField>
                                <InputIcon>
                                    <i class="pi pi-search" />
                                </InputIcon>
                                <InputText v-model="filters['global'].value" placeholder="Kata Kunci" class="w-full" />
                            </IconField>
                        </div>
                    </template>

                    <template #loading>
                        <span class="flex justify-center">Sedang Memuat Data...</span>
                    </template>

                    <template #empty>
                        <span class="flex justify-center">Tidak Ada Data Rumah</span>
                    </template>

                    <Column header="No">
                        <template #body="slotProps">
                            {{ slotProps.index + 1 }}
                        </template>
                    </Column>
                    <Column style="min-width: 200px" field="nama" header="Nama" />
                    <Column style="min-width: 200px" field="tipe" header="Tipe" />
                    <Column style="min-width: 200px" field="luas_bangunan" header="Luas Bangunan (m²)" />
                    <Column style="min-width: 200px" field="luas_tanah" header="Luas Tanah (m²)" />
                    <Column style="min-width: 200px" field="harga" header="Harga (Rp)">
                        <template #body="{ data }">
                            {{ formatRupiah(data.harga) }}
                        </template>
                    </Column>
                    <Column style="min-width: 200px" field="karakteristik" header="Karakteristik" />

                    <Column frozen align-frozen="right">
                        <template #body="slotProps">
                            <div class="flex justify-center gap-2">
                                <Button
                                    raised
                                    rounded
                                    aria-label="Edit"
                                    size="small"
                                    icon="pi pi-pencil"
                                    text
                                    @click="goToEdit(slotProps.data.id_rumah)"
                                />
                                <Button
                                    raised
                                    rounded
                                    aria-label="Hapus"
                                    size="small"
                                    icon="pi pi-trash"
                                    text
                                    severity="danger"
                                    @click="confirmDelete(slotProps.data.id_rumah)"
                                />
                            </div>
                        </template>
                    </Column>
                </DataTable>
            </template>
        </Card>
    </AppLayout>
</template>
