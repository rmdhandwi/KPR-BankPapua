<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Hasil, SharedData, type BreadcrumbItem } from '@/types';
import { Head, router, usePage } from '@inertiajs/vue3';
import { FilterMatchMode } from '@primevue/core/api';
import type { FileUploadSelectEvent } from 'primevue/fileupload';
import { onMounted, ref, watch } from 'vue';
import { useToast } from 'vue-toast-notification';

const page = usePage<SharedData>();
const toast = useToast();

const hasil = ref<Hasil[]>([]);
const loading = ref(true);

const breadcrumbItems: BreadcrumbItem[] = [{ title: 'Laporan', href: '/admin/laporan/' }];

// Data awal
onMounted(() => {
    hasil.value = page.props.hasil ?? [];
    loading.value = false;
});

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

// Upload file dialog
const showDialog = ref(false);
const selectedHasil = ref<Hasil | null>(null);
const file = ref<File | null>(null);

// Buka dialog upload
function openDialog(idPerhitungan: number) {
    const data = hasil.value.find((item) => item.id_perhitungan === idPerhitungan);
    if (data) {
        selectedHasil.value = data;
        showDialog.value = true;
    } else {
        toast.error('Data perhitungan tidak ditemukan.');
    }
}

// Simpan file yang dipilih
function onFileSelect(event: FileUploadSelectEvent) {
    file.value = event.files[0];
}

// Format tanggal ID
function formatTanggal(tanggal: string): string {
    return new Date(tanggal).toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
    });
}

const sending = ref(false); // 👈 state loading

// Kirim form ke server
function submit() {
    if (!file.value) {
        toast.error('Silakan pilih file PDF terlebih dahulu!');
        return;
    }

    if (!selectedHasil.value?.id_nasabah) {
        toast.error('Data nasabah tidak valid.');
        return;
    }

    const formData = new FormData();
    formData.append('id_nasabah', selectedHasil.value.id_nasabah.toString());
    formData.append('file', file.value);

    router.post(route('admin.laporan.kirim-persetujuan'), formData, {
        forceFormData: true,
        onSuccess: () => {
            toast.success('File berhasil dikirim!');
            showDialog.value = false;
            file.value = null;
        },
        onError: () => {
            toast.error('Gagal mengirim file!');
        },
        onFinish: () => {
            sending.value = false;
        },
    });
}

function printLaporan() {
    router.post(route('admin.laporan.print'));
}

// Flash error
watch(
    () => page.props.flash?.error,
    (message) => {
        if (message) {
            toast.error(message, { position: 'top-right' });
        }
    },
    { immediate: true },
);
</script>

<template>
    <Head title="Laporan Hasil Layak" />
    <AppLayout :breadcrumbs="breadcrumbItems">
        <HeadingSmall title="Laporan Hasil" description="Daftar nasabah yang dinyatakan layak atau tidak" />

        <Card class="mt-4">
            <template #content>
                <DataTable
                    :value="hasil"
                    :loading="loading"
                    size="small"
                    columnResizeMode="fit"
                    :filters="filters"
                    paginator
                    paginatorTemplate="RowsPerPageDropdown FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink"
                    currentPageReportTemplate="{first} ke {last} dari {totalRecords}"
                    :rows="10"
                    scrollable
                    scrollHeight="500px"
                    :rowsPerPageOptions="[5, 10, 20, 50, 100]"
                    dataKey="id_perhitungan"
                    responsiveLayout="scroll"
                    sortMode="single"
                    :sortOrder="1"
                >
                    <template #header>
                        <div class="mb-3 flex justify-between">
                            <IconField>
                                <InputIcon>
                                    <i class="pi pi-search" />
                                </InputIcon>
                                <InputText v-model="filters['global'].value" placeholder="Kata Kunci" class="w-full" />
                            </IconField>
                            <div>
                                <Button @click="printLaporan" label="Print" raised rounded aria-label="Print" size="small" icon="pi pi-print" text />
                            </div>
                        </div>
                    </template>

                    <template #loading>
                        <span class="flex justify-center">Sedang Memuat Data...</span>
                    </template>

                    <template #empty>
                        <span class="flex justify-center">Tidak Ada Data Laporan</span>
                    </template>

                    <Column header="No">
                        <template #body="data">
                            {{ data.index + 1 }}
                        </template>
                    </Column>

                    <Column field="nama_lengkap" header="Nama Nasabah" />
                    <Column field="email" header="Email" />
                    <Column field="nama" header="Nama Rumah" />
                    <Column field="tipe" header="Tipe Rumah" />
                    <Column field="skor_akhir" header="Skor Akhir" />

                    <Column field="status_kelayakan" header="Status">
                        <template #body="{ data }">
                            <Tag
                                :value="data.status_kelayakan"
                                :severity="data.status_kelayakan === 'Layak' ? 'success' : 'danger'"
                                :icon="data.status_kelayakan === 'Layak' ? 'pi pi-check-circle' : 'pi pi-times-circle'"
                                class="capitalize"
                            />
                        </template>
                    </Column>

                    <Column field="tgl_hitung" header="Tanggal Hitung">
                        <template #body="{ data }">
                            {{ formatTanggal(data.tgl_hitung) }}
                        </template>
                    </Column>

                    <Column>
                        <template #body="{ data }">
                            <Button
                                v-if="data.status_kelayakan === 'Layak'"
                                icon="pi pi-envelope"
                                label="Kirim Persetujuan"
                                severity="info"
                                size="small"
                                text
                                rounded
                                @click="openDialog(data.id_perhitungan)"
                            />
                        </template>
                    </Column>
                </DataTable>
            </template>
        </Card>
    </AppLayout>

    <!-- Dialog Upload File PDF -->
    <Dialog v-model:visible="showDialog" header="Kirim Persetujuan KPR" modal>
        <div class="mb-3">
            <FileUpload
                :customUpload="true"
                :auto="false"
                :showUploadButton="false"
                :showCancelButton="false"
                accept="application/pdf"
                chooseLabel="Pilih File PDF"
                @select="onFileSelect"
            />
            <small v-if="!file" class="p-error">* File PDF harus dipilih</small>
        </div>

        <div class="mt-4 text-right">
            <Button
                :label="sending ? 'Mengirim...' : 'Kirim'"
                :icon="sending ? 'pi pi-spin pi-spinner' : 'pi pi-send'"
                :disabled="!file || sending"
                @click="submit"
            />
        </div>
    </Dialog>
</template>
