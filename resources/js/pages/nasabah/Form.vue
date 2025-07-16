<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import Label from '@/components/ui/label/Label.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { SharedData } from '@/types';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { ConfirmDialog, DatePicker, Message, Select, useConfirm } from 'primevue';
import { computed, onMounted } from 'vue';
import { useToast } from 'vue-toast-notification';

import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import RadioButton from 'primevue/radiobutton';
import { ref } from 'vue';

const page = usePage<SharedData>();
const toast = useToast();
const confirm = useConfirm();

const breadcrumbItems = [
    {
        title: 'Form Data Nasabah',
        href: '/developer/nasabah/create',
    },
];

function formatDateToYMD(date: Date | string): string {
    const d = new Date(date);
    return d.toISOString().split('T')[0]; // output: "2025-06-30"
}

const props = defineProps<{
    data?: Record<string, any> | null;
    columns: string[];
    rumah: { id_rumah: number; nama: string; tipe: string }[];
}>();

const options: Record<string, string[]> = {
    penghasilan: ['< Rp. 7.500.000', 'Rp. 7.600.000–8.000.000', 'Rp. 9.000.000-10.000.000', 'Rp. 10.000.000-15.000.000', '> Rp. 15.000.000'],
    status_perkawinan: ['Belum Menikah', 'Sudah Menikah', 'Cerai'],
    riwayat_pinjol: ['Sedang Meminjam', 'Pernah Meminjam', 'Belum Pernah Meminjam'],
    riwayat_kredit: ['Kredit Macet', 'Belum Pernah Kredit', 'Kredit Lancar'],
    jml_tanggungan: ['1 Orang', '2-5 Orang', '> 5 Orang'],
};

const fixedFields: { key: string; label: string }[] = [
    { key: 'id_rumah', label: 'Pilih Rumah' },
    { key: 'nama_lengkap', label: 'Nama Lengkap' },
    { key: 'no_ktp', label: 'Nomor KTP' },
    { key: 'no_kk', label: 'Nomor KK' },
    { key: 'no_npwp', label: 'Nomor NPWP' },
    { key: 'tempat_lahir', label: 'Tempat Lahir' },
    { key: 'tgl_lahir', label: 'Tanggal Lahir' },
    { key: 'pekerjaan', label: 'Pekerjaan' },
    { key: 'nama_perusahan', label: 'Nama Perusahaan' },
    { key: 'alamat_perusahaan', label: 'Alamat Perusahaan' },
    { key: 'email', label: 'Email' },
    { key: 'no_tlp', label: 'Nomor Telepon' },
    { key: 'kewarganegaraan', label: 'Kewarganegaraan' },
    { key: 'jml_tanggungan', label: 'Jumlah Tanggungan' },
    { key: 'penghasilan', label: 'Penghasilan Per Bulan' },
    { key: 'status_perkawinan', label: 'Status Perkawinan' },
    { key: 'riwayat_pinjol', label: 'Riwayat Pinjaman Online' },
    { key: 'riwayat_kredit', label: 'Riwayat Kredit' },
];

const radioFields = fixedFields.filter((f) => Object.keys(options).includes(f.key));
const inputFields = fixedFields.filter((f) => !Object.keys(options).includes(f.key));

const form = useForm(
    props.columns.reduce(
        (acc, col) => {
            acc[col] = props.data?.[col] ?? '';
            return acc;
        },
        {} as Record<string, any>,
    ),
);

const files = ref<Record<string, File | null>>({
    KTP: null,
    NPWP: null,
    surat_nikah: null,
    spt_tahunan: null,
    kartu_keluarga: null,
});

const rumahOptions = computed(() =>
    props.rumah.map((r) => ({
        label: `${r.nama} - ${r.tipe}`,
        value: r.id_rumah,
    })),
);

const dynamicFields = computed(() => {
    const start = props.columns.indexOf('kelengkapan_berkas');
    return props.columns.slice(start + 1).filter((c) => c !== 'created_at' && c !== 'updated_at');
});

onMounted(() => {
    if (props.data) {
        Object.keys(files.value).forEach((key) => {
            const filePath = props.data?.[key];
            if (filePath) {
                srcs.value[key] = `/storage/${filePath}`;
                form[key] = filePath;
            }
        });
    }
});

function submit() {
    const isEdit = !!props.data;
    const url = isEdit ? route('developer.nasabah.update', props.data?.id_nasabah) : route('developer.nasabah.store');

    // Format tanggal sebelum submit
    Object.keys(form.data()).forEach((key) => {
        if (/tanggal|tgl_/i.test(key) && form[key]) {
            form[key] = formatDateToYMD(form[key]);
        }
    });

    const formData = new FormData();
    // Tambahkan data teks
    for (const key in form.data()) {
        if (key === 'kelengkapan_berkas') continue;

        // Jangan kirim path file ke FormData jika tidak diganti
        if (Object.keys(files.value).includes(key)) {
            // Kalau tidak ada file baru diupload, kirim value sebagai text biasa (bukan FormData file)
            if (!files.value[key]) {
                formData.append(key, form[key]); // tetap kirim path-nya
            }
            continue; // agar tidak double append nanti di bagian File
        }

        formData.append(key, form[key] ?? '');
    }

    // Tambahkan file
    Object.entries(files.value).forEach(([key, file]) => {
        if (file instanceof File) {
            formData.append(key, file);
        }
    });

    const postAction = () => {
        router.post(url, formData, {
            forceFormData: true,
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                toast.success(page.props.flash?.success || (isEdit ? 'Data berhasil diperbarui' : 'Data berhasil disimpan'), {
                    position: 'top-right',
                });
                form.clearErrors(); // bersihkan error
            },
            onError: (errors) => {
                // Salin error manual ke form agar binding tetap jalan
                form.setError(errors);
                toast.error(page.props.flash?.error || 'Gagal menyimpan data', { position: 'top-right' });
            },
        });
    };

    if (isEdit) {
        confirm.require({
            message: 'Yakin ingin mengubah data nasabah ini?',
            header: 'Konfirmasi',
            accept: postAction,
            reject: () => toast.info('Batal mengubah', { position: 'top-right' }),
        });
    } else {
        postAction();
    }
}

const srcs = ref<Record<string, string | null>>({});
const allowedImage = ['image/jpeg', 'image/png', 'image/jpg'];
const allowedPdf = ['application/pdf'];

function onFileSelect(event: any, key: string) {
    const file = event.files?.[0];
    if (!file) return;

    const isImage = allowedImage.includes(file.type);
    const isPdf = allowedPdf.includes(file.type);

    if (!isImage && !isPdf) {
        toast.error('File harus berupa gambar atau PDF', { position: 'top-right' });
        files.value[key] = null;
        return;
    }

    files.value[key] = file;

    const reader = new FileReader();
    reader.onload = (e) => {
        srcs.value[key] = e.target?.result as string;
    };
    reader.readAsDataURL(file);
}
</script>

<template>
    <ConfirmDialog />
    <Head :title="props.data ? 'Edit Nasabah' : 'Tambah Nasabah'" />
    <AppLayout :breadcrumbs="breadcrumbItems">
        <div class="mx-full space-y-6 p-6">
            <HeadingSmall
                :title="props.data ? 'Edit Nasabah' : 'Tambah Nasabah'"
                description="Form data nasabah. Kolom setelah 'kelengkapan_berkas' bersifat dinamis."
            />

            <!-- Static Input Fields -->
            <div class="grid grid-cols-2 gap-4">
                <div v-for="{ key, label } in inputFields" :key="key" class="flex flex-col gap-1">
                    <Label :for="key">{{ label }}</Label>

                    <!-- Select -->
                    <Select
                        v-if="key === 'id_rumah'"
                        v-model="form[key]"
                        :options="rumahOptions"
                        option-label="label"
                        option-value="value"
                        placeholder="Pilih Rumah"
                        class="w-full"
                        :invalid="!!form.errors[key]"
                    />

                    <!-- Tanggal -->
                    <DatePicker
                        v-else-if="/tanggal|tgl_/i.test(key)"
                        v-model="form[key]"
                        dateFormat="yy/mm/dd"
                        showIcon
                        iconDisplay="input"
                        class="w-full"
                        :invalid="!!form.errors[key]"
                    />

                    <!-- Input Text -->
                    <InputText
                        v-else
                        v-model="form[key]"
                        :maxlength="['no_ktp', 'no_kk'].includes(key) ? 16 : undefined"
                        :id="key"
                        class="w-full"
                        :invalid="!!form.errors[key]"
                    />

                    <!-- Error Message -->
                    <Message v-if="form.errors[key]" severity="error" size="small" variant="simple">
                        {{ form.errors[key] }}
                    </Message>
                </div>
            </div>

            <!-- Radio Button Fields -->
            <div class="mt-6 grid grid-cols-1 gap-4 border-t pt-4 sm:grid-cols-2 lg:grid-cols-5">
                <div v-for="{ key, label } in radioFields" :key="key" class="flex flex-col gap-2">
                    <Label :for="key">{{ label }}</Label>

                    <div class="flex flex-col gap-2">
                        <div v-for="opt in options[key]" :key="opt" class="flex items-center gap-2">
                            <RadioButton :inputId="key + opt" :value="opt" v-model="form[key]" />
                            <label :for="key + opt">{{ opt }}</label>
                        </div>
                    </div>

                    <Message v-if="form.errors[key]" severity="error" size="small" variant="simple">
                        {{ form.errors[key] }}
                    </Message>
                </div>
            </div>

            <!-- Dynamic Fields -->
            <template v-if="dynamicFields.length">
                <div class="col-span-2 border-t pt-4">
                    <h3 class="mb-3 text-lg font-semibold">Kolom Dinamis</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div v-for="col in dynamicFields" :key="col" class="flex flex-col gap-1">
                            <Label :for="col" class="capitalize">{{ col.replace(/_/g, ' ') }}</Label>
                            <InputText :id="col" v-model="form[col]" class="w-full" :invalid="!!form.errors[col]" />
                            <Message v-if="form.errors[col]" severity="error" size="small" variant="simple">
                                {{ form.errors[col] }}
                            </Message>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Upload Dokumen -->
            <div class="col-span-2 border-t pt-4">
                <h3 class="mb-3 text-lg font-semibold">Upload Dokumen</h3>
                <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
                    <div v-for="doc in ['KTP', 'NPWP', 'surat_nikah', 'spt_tahunan', 'kartu_keluarga']" :key="doc" class="space-y-2">
                        <Label :for="doc" class="capitalize">{{ doc.replace(/_/g, ' ') }}</Label>
                        <FileUpload
                            mode="basic"
                            name="file"
                            :chooseLabel="form[doc] && !files[doc] ? 'Ganti File' : 'Pilih File'"
                            customUpload
                            auto
                            accept=".jpg,.jpeg,.png,.pdf"
                            class="w-full"
                            @select="(e) => onFileSelect(e, doc)"
                        />

                        <!-- Preview -->
                        <div v-if="srcs[doc]" class="mt-2">
                            <img
                                v-if="/^data:image/.test(srcs[doc]!) || srcs[doc]?.endsWith('.jpg') || srcs[doc]?.endsWith('.png')"
                                :src="srcs[doc]"
                                alt="Preview"
                                class="h-52 w-full rounded border shadow"
                            />
                            <div v-else-if="srcs[doc]?.endsWith('.pdf')" class="flex items-center gap-2 text-gray-600">
                                <FilePdf class="h-6 w-6 text-red-500" />
                                <span class="text-sm">File PDF tersedia</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="col-span-2 mt-4 flex justify-between gap-3">
                <Button as="a" label="Kembali" type="button" :href="route('developer.nasabah.index')" severity="secondary" />
                <Button :label="props.data ? 'Update' : 'Simpan'" @click="submit" :loading="form.processing" />
            </div>
        </div>
    </AppLayout>
</template>
