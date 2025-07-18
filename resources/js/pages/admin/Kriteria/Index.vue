<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import AdminLayout from '@/layouts/admin/Kriteria.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Kriteria, SharedData, type BreadcrumbItem } from '@/types';
import { Head, router, usePage } from '@inertiajs/vue3';
import { FilterMatchMode } from '@primevue/core/api';
import { ConfirmDialog, useConfirm } from 'primevue';
import { onMounted, ref, watch } from 'vue';
import { useToast } from 'vue-toast-notification';

const page = usePage<SharedData>();
const kriteria = ref<Kriteria[]>(page.props.kriteria || []);
const toast = useToast();
const confirm = useConfirm();
const loading = ref(true);

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

onMounted(() => {
    loading.value = false;
});

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Kriteria',
        href: '/admin/kriteria',
    },
];

function goToEdit(id: number) {
    router.post(route('admin.kriteria.edit.init', { id }));
}

watch(
    () => page.props.flash?.error,
    (message) => {
        if (message) {
            toast.error(message, { position: 'top-right', duration: 3000 });
        }
    },
    { immediate: true },
);

function confirmDelete(id: number) {
    confirm.require({
        message: 'Apakah Anda yakin ingin menghapus data kriteria ini?',
        header: 'Konfirmasi Hapus',
        icon: 'pi pi-info-circle',
        rejectLabel: 'Batal',
        rejectProps: {
            label: 'Batal',
            severity: 'secondary',
            outlined: true,
        },
        acceptProps: {
            label: 'Hapus',
            severity: 'danger',
        },
        accept: () => doDestroy(id),
        reject: () => {
            toast.info('Batal menghapus data', { position: 'top-right' });
        },
    });
}

function doDestroy(id: number) {
    router.delete(route('admin.kriteria.destroy', id), {
        onSuccess: () => {
            const message = page.props.flash?.success;
            if (message) {
                toast.success(message, { position: 'top-right', duration: 3000 });
            }
            router.visit(route('admin.kriteria.index'));
        },
        onError: () => {
            const message = page.props.flash?.error ?? 'Gagal menghapus data.';
            toast.error(message, { position: 'top-right', duration: 3000 });
        },
    });
}
</script>

<template>
    <Head title="Kriteria" />
    <ConfirmDialog />
    <AppLayout :breadcrumbs="breadcrumbItems">
        <AdminLayout>
            <div class="space-y-6">
                <HeadingSmall title="Data Kriteria" description="Daftar Kriteria" />
                <Card>
                    <template #content>
                        <DataTable
                            size="small"
                            columnResizeMode="fit"
                            :filters="filters"
                            :value="kriteria"
                            :loading="loading"
                            paginator
                            paginatorTemplate="RowsPerPageDropdown FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink"
                            currentPageReportTemplate="{first} ke {last} dari {totalRecords}"
                            :rows="10"
                            scrollable
                            scrollHeight="500px"
                            :rowsPerPageOptions="[5, 10, 20, 50, 100]"
                            dataKey="id_kriteria"
                            responsiveLayout="scroll"
                        >
                            <template #header>
                                <div class="mb-3">
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
                                <span class="flex justify-center">Tidak Ada Kriteria</span>
                            </template>

                            <Column header="No">
                                <template #body="slotProps">
                                    {{ slotProps.index + 1 }}
                                </template>
                            </Column>

                            <Column field="nama_kriteria" header="Nama Kriteria" style="min-width: 150px" />
                            <Column field="bobot" header="Bobot(%)" style="min-width: 150px" />
                            <Column field="jenis" header="Tipe" style="min-width: 150px" />

                            <Column frozen align-frozen="right">
                                <template #body="slotProps">
                                    <div class="flex place-content-center gap-2">
                                        <Button
                                            raised
                                            rounded
                                            aria-label="Edit"
                                            size="small"
                                            icon="pi pi-pencil"
                                            text
                                            @click="goToEdit(slotProps.data.id_kriteria)"
                                        />
                                        <Button
                                            raised
                                            rounded
                                            aria-label="Hapus"
                                            size="small"
                                            icon="pi pi-trash"
                                            text
                                            severity="danger"
                                            @click="confirmDelete(slotProps.data.id_kriteria)"
                                        />
                                    </div>
                                </template>
                            </Column>
                        </DataTable>
                    </template>
                </Card>
            </div>
        </AdminLayout>
    </AppLayout>
</template>
