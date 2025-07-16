<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { useConfirm } from 'primevue/useconfirm';
import { computed, onMounted, ref } from 'vue';
import { useToast } from 'vue-toast-notification';

import { SharedData } from '@/types';
import { LoaderCircle } from 'lucide-vue-next';
import { ConfirmDialog, Select } from 'primevue';
import Button from 'primevue/button';
import Card from 'primevue/card';
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';

const page = usePage<SharedData>();
const toast = useToast();
const confirm = useConfirm();

const nasabahData = ref<any[]>(page.props.nasabah || []);
const allColumns = ref<string[]>(page.props.columns || []);
const loading = ref(true);

const role = page.props.auth.user.role;

onMounted(() => {
    loading.value = false;
});

// Kolom tetap yang ditampilkan di tabel
const fixedColumns = ['nama_lengkap', 'no_ktp', 'email', 'penghasilan', 'kelengkapan_berkas'];

const hiddenFieldDetails = [
    'id_nasabah',
    'id_rumah',
    'created_at',
    'updated_at',
    'rumah',
    'NPWP',
    'KTP',
    'surat_nikah',
    'spt_tahunan',
    'kartu_keluarga',
    'kelengkapan_berkas',
];

// Kolom yang bisa dihapus (dinamis)
const customColumns = computed(() =>
    allColumns.value.filter(
        (col) =>
            ![
                'id_nasabah',
                'id_rumah',
                'nama_lengkap',
                'no_ktp',
                'no_kk',
                'no_npwp',
                'tempat_lahir',
                'tgl_lahir',
                'pekerjaan',
                'nama_perusahan',
                'alamat_perusahaan',
                'email',
                'penghasilan',
                'no_tlp',
                'kewarganegaraan',
                'status_perkawinan',
                'jml_tanggungan',
                'riwayat_pinjol',
                'riwayat_kredit',
                'NPWP',
                'KTP',
                'surat_nikah',
                'spt_tahunan',
                'kartu_keluarga',
                'kelengkapan_berkas',
                'created_at',
                'updated_at',
            ].includes(col),
    ),
);
const kelengkapanDialog = ref(false);
const selectedKelengkapan = ref<'Lengkap' | 'Tidak Lengkap' | null>(null);
const selectedNasabahId = ref<number | null>(null);
const kelengkapanForm = useForm({
    kelengkapan_berkas: '',
});

function openKelengkapanDialog(nasabah: any) {
    selectedNasabahId.value = nasabah.id_nasabah;
    selectedKelengkapan.value = nasabah.kelengkapan_berkas ?? 'Tidak Lengkap';
    kelengkapanForm.kelengkapan_berkas = selectedKelengkapan.value;
    kelengkapanDialog.value = true;
}

function updateKelengkapanStatus() {
    if (!selectedNasabahId.value) return;

    kelengkapanForm.put(route('admin.nasabah.update-kelengkapan', selectedNasabahId.value), {
        onSuccess: () => {
            toast.success('Kelengkapan berhasil diperbarui');
            kelengkapanDialog.value = false;
            router.visit(route('admin.nasabah.index')); // reload data
        },
        onError: () => toast.error('Gagal memperbarui kelengkapan', { position: 'top-right' }),
    });
}

// Form tambah/hapus kolom
const form = useForm({ name: '' });
const newColumnName = ref('');
const columnToDelete = ref('');
const showAddDialog = ref(false);
const showDeleteDialog = ref(false);

// Detail nasabah
const dialogVisible = ref(false);
const selectedNasabah = ref<Record<string, any> | null>(null);

function showDetails(data: Record<string, any>) {
    selectedNasabah.value = data;
    dialogVisible.value = true;
}

function goToEdit(id: number) {
    router.post(route('developer.nasabah.edit.init', { id }));
}

function confirmDelete(id: number) {
    confirm.require({
        message: 'Yakin ingin menghapus data ini?',
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
    router.delete(route('developer.nasabah.destroy', id), {
        onSuccess: () => {
            toast.success('Data berhasil dihapus', { position: 'top-right' });
            router.visit(route('developer.nasabah.index'));
        },
    });
}

function submitNewColumn() {
    form.name = newColumnName.value;
    form.post(route('developer.nasabah.add-column'), {
        onSuccess: () => {
            showAddDialog.value = false;
            newColumnName.value = '';
            toast.success('Kolom berhasil ditambahkan', { position: 'top-right' });
            router.visit(route('developer.nasabah.index'));
        },
        onError: () => toast.error('Kolom gagal ditambahkan', { position: 'top-right' }),
    });
}

function submitDeleteColumn() {
    form.name = columnToDelete.value;
    form.post(route('developer.nasabah.remove-column'), {
        onSuccess: () => {
            showDeleteDialog.value = false;
            columnToDelete.value = '';
            toast.success('Kolom berhasil dihapus', { position: 'top-right' });
            router.visit(route('developer.nasabah.index'));
        },
        onError: () => toast.error('Kolom gagal dihapus', { position: 'top-right' }),
    });
}

const deletableColumns = computed(() => customColumns.value.map((col) => ({ label: col, value: col })));

const showFileDialog = ref(false);

function showDocuments(data: Record<string, any>) {
    selectedNasabah.value = data;
    showFileDialog.value = true;
}
</script>

<template>
    <ConfirmDialog />
    <Head title="Data Nasabah" />
    <AppLayout>
        <div class="space-y-6 p-6">
            <HeadingSmall title="Data Nasabah" description="Daftar nasabah" />

            <template v-if="role == 2">
                <div class="flex items-center justify-between">
                    <Button size="small" label="Tambah Nasabah" as="a" icon="pi pi-plus" severity="info" :href="route('developer.nasabah.create')" />
                    <div class="flex gap-2">
                        <Button size="small" label="Tambah Kolom" icon="pi pi-plus" severity="success" @click="showAddDialog = true" />
                        <Button size="small" label="Hapus Kolom" icon="pi pi-minus" severity="danger" @click="showDeleteDialog = true" />
                    </div>
                </div>
            </template>

            <Card>
                <template #content>
                    <DataTable :value="nasabahData" :loading="loading" paginator :rows="10">
                        <Column header="No">
                            <template #body="slotProps">
                                {{ slotProps.index + 1 }}
                            </template>
                        </Column>
                        <Column class="capitalize" v-for="col in fixedColumns" :key="col" :field="col" :header="col.replace(/_/g, ' ')">
                            <template v-if="col === 'kelengkapan_berkas'" #body="{ data }">
                                <Tag v-if="data.kelengkapan_berkas === 'Lengkap'" icon="pi pi-check" severity="success" value="Lengkap" />
                                <Tag
                                    v-else-if="data.kelengkapan_berkas === 'Tidak Lengkap'"
                                    icon="pi pi-exclamation-triangle"
                                    severity="warning"
                                    value="Tidak Lengkap"
                                />
                                <span v-else class="text-gray-400 italic">Belum dikonfirmasi</span>
                            </template>
                        </Column>

                        <Column class="flex gap-2" header="Aksi">
                            <template #body="{ data }">
                                <Button raised rounded aria-label="Detail" size="small" icon="pi pi-eye" text @click="showDetails(data)" />
                                <Button
                                    raised
                                    rounded
                                    aria-label="Dokumen"
                                    size="small"
                                    icon="pi pi-file"
                                    text
                                    severity="secondary"
                                    @click="showDocuments(data)"
                                />
                                <Button
                                    v-if="role == 2"
                                    raised
                                    rounded
                                    aria-label="Edit"
                                    size="small"
                                    icon="pi pi-pencil"
                                    text
                                    @click="goToEdit(data.id_nasabah)"
                                />
                                <Button
                                    v-if="role == 2"
                                    raised
                                    rounded
                                    aria-label="Hapus"
                                    size="small"
                                    icon="pi pi-trash"
                                    text
                                    severity="danger"
                                    @click="confirmDelete(data.id_nasabah)"
                                />
                                <Button
                                    v-if="role == 1"
                                    raised
                                    rounded
                                    size="small"
                                    :icon="data.kelengkapan_berkas === 'Lengkap' ? 'pi pi-check-circle' : 'pi pi-times-circle'"
                                    :label="data.kelengkapan_berkas === 'Lengkap' ? 'Lengkap' : 'Tidak Lengkap'"
                                    :severity="data.kelengkapan_berkas === 'Lengkap' ? 'success' : 'danger'"
                                    text
                                    @click="openKelengkapanDialog(data)"
                                />
                            </template>
                        </Column>
                    </DataTable>
                </template>
            </Card>
        </div>
    </AppLayout>

    <!-- Dialog Tambah Kolom -->
    <Dialog v-model:visible="showAddDialog" header="Tambah Kolom Baru" :modal="true" :closable="true">
        <div class="space-y-3">
            <InputText v-model="newColumnName" placeholder="nama_kolom" class="w-full" />
            <Message v-if="form.errors.name" severity="error" size="small" variant="simple">{{ form.errors.name }}</Message>
            <Button fluid label="Simpan" @click="submitNewColumn" />
        </div>
    </Dialog>

    <!-- Dialog Hapus Kolom -->
    <Dialog v-model:visible="showDeleteDialog" header="Hapus Kolom" :modal="true" :closable="true">
        <div class="space-y-3">
            <label for="deleteCol" class="block text-sm font-semibold">Pilih Kolom yang Akan Dihapus</label>

            <Select
                v-model="columnToDelete"
                :options="deletableColumns"
                optionLabel="label"
                optionValue="value"
                placeholder="Pilih Kolom"
                class="w-full md:w-56"
            />

            <Message variant="simple" size="small" v-if="form.errors.name" severity="error">{{ form.errors.name }}</Message>

            <div class="mt-4 flex justify-end gap-2">
                <Button size="small" label="Batal" severity="secondary" @click="showDeleteDialog = false" />
                <Button size="small" label="Hapus" severity="danger" :disabled="!columnToDelete || form.processing" @click="submitDeleteColumn">
                    <template #default>
                        <span v-if="form.processing" class="flex items-center justify-center gap-2">
                            <LoaderCircle class="h-4 w-4 animate-spin" />
                            Memproses...
                        </span>
                    </template>
                </Button>
            </div>
        </div>
    </Dialog>

    <!-- Dialog Detail -->
    <Dialog v-model:visible="dialogVisible" header="Detail Nasabah" :modal="true" :style="{ width: '600px' }">
        <div v-if="selectedNasabah" class="space-y-3 px-4 py-2">
            <template v-for="(value, key) in selectedNasabah" :key="key">
                <div v-if="!hiddenFieldDetails.includes(key)" class="grid grid-cols-2 items-start gap-2">
                    <div class="text-sm font-medium text-gray-600 capitalize">
                        <strong>{{ key.replace(/_/g, ' ') }}</strong>
                    </div>
                    <div class="text-sm text-gray-800">
                        {{ value ?? '-' }}
                    </div>
                </div>
            </template>

            <!-- Tampilkan data rumah jika tersedia -->
            <div class="grid grid-cols-2 items-start gap-2" v-if="selectedNasabah.rumah">
                <div class="text-sm font-medium text-gray-600">Rumah (Nama)</div>
                <div class="text-sm text-gray-800">{{ selectedNasabah.rumah.nama }}</div>

                <div class="text-sm font-medium text-gray-600">Rumah (Tipe)</div>
                <div class="text-sm text-gray-800">{{ selectedNasabah.rumah.tipe }}</div>
            </div>
        </div>
    </Dialog>

    <!-- Dokumen File -->
    <Dialog v-model:visible="showFileDialog" header="Dokumen Nasabah" :modal="true" :style="{ width: '600px' }">
        <div v-if="selectedNasabah" class="space-y-3">
            <div class="grid grid-cols-2 gap-4">
                <div v-for="field in ['KTP', 'NPWP', 'surat_nikah', 'spt_tahunan', 'kartu_keluarga']" :key="field" class="flex flex-col gap-2">
                    <span class="text-sm font-medium text-gray-600 capitalize">{{ field.replace(/_/g, ' ') }}</span>

                    <template v-if="selectedNasabah && selectedNasabah[field]">
                        <img
                            v-if="/\.(jpg|jpeg|png)$/i.test(selectedNasabah[field])"
                            :src="`/${selectedNasabah[field]}`"
                            alt="Preview"
                            class="h-52 w-auto rounded border shadow"
                        />

                        <Button
                            v-else-if="/\.pdf$/i.test(selectedNasabah[field])"
                            :href="`/${selectedNasabah[field]}`"
                            target="_blank"
                            as="a"
                            rounded
                            raised
                            aria-label="PDF"
                            icon="pi pi-file-pdf"
                            label="Lihat PDF"
                            severity="danger"
                        />

                        <span v-else class="text-sm text-gray-500">Format tidak didukung</span>
                    </template>

                    <template v-else>
                        <span class="text-sm text-gray-400 italic">Belum diunggah</span>
                    </template>
                </div>
            </div>
        </div>
    </Dialog>

    <Dialog v-model:visible="kelengkapanDialog" header="Update Kelengkapan Berkas" modal :closable="true">
        <div class="space-y-4">
            <div>
                <label class="mb-1 block text-sm font-medium">Status Kelengkapan</label>
                <Select
                    v-model="kelengkapanForm.kelengkapan_berkas"
                    :options="['Lengkap', 'Tidak Lengkap']"
                    placeholder="Pilih status"
                    class="w-full"
                />
                <Message v-if="kelengkapanForm.errors.kelengkapan_berkas" severity="error" size="small">
                    {{ kelengkapanForm.errors.kelengkapan_berkas }}
                </Message>
            </div>
            <div class="flex justify-end gap-2">
                <Button label="Batal" size="small" severity="secondary" @click="kelengkapanDialog = false" />
                <Button label="Simpan" size="small" :disabled="kelengkapanForm.processing" @click="updateKelengkapanStatus()">
                    <template #default>
                        <span v-if="kelengkapanForm.processing" class="flex items-center justify-center gap-2">
                            <LoaderCircle class="h-4 w-4 animate-spin" />
                            Memproses...
                        </span>
                    </template>
                </Button>
            </div>
        </div>
    </Dialog>
</template>

<style scoped></style>
