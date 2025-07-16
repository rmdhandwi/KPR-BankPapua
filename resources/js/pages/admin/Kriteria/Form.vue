<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import Label from '@/components/ui/label/Label.vue';
import AdminLayout from '@/layouts/admin/Kriteria.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { SharedData, type BreadcrumbItem } from '@/types';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ConfirmDialog, Select, useConfirm } from 'primevue';
import { ref } from 'vue';
import { useToast } from 'vue-toast-notification';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Form Kriteria',
        href: '/kriteria/create',
    },
];

const props = defineProps<{
    data?: {
        id_kriteria?: number;
        nama_kriteria: string;
        bobot: string;
        jenis: string;
    };
}>();

const tipeKriteria = ref([
    { name: 'Benefit', value: 'Benefit' },
    { name: 'Cost', value: 'Cost' },
]);

const page = usePage<SharedData>();
const loading = ref(false);
const confirm = useConfirm();
const toast = useToast();

// Setup form dengan default value dari props.data jika ada (edit mode)
const form = useForm({
    id_kriteria: props.data?.id_kriteria ?? null,
    nama_kriteria: props.data?.nama_kriteria ?? '',
    bobot: props.data?.bobot ?? '',
    jenis: props.data?.jenis ?? '',
});

// Submit function
function submit() {
    if (props.data) {
        // Konfirmasi sebelum edit
        confirm.require({
            message: 'Apakah Anda yakin ingin mengubah data ini?',
            header: 'Konfirmasi',
            icon: 'pi pi-exclamation-triangle',
            rejectProps: {
                label: 'Batal',
                severity: 'secondary',
                outlined: true,
            },
            acceptProps: {
                label: 'Ya',
                severity: 'primary',
            },
            accept: () => doSubmit(),
            reject: () => {
                toast.info('Batal mengupdate data', { position: 'top-right' });
            },
        });
    } else {
        // Langsung submit saat tambah
        doSubmit();
    }
}

function doSubmit() {
    const isEdit = !!props.data;

    const url = isEdit ? route('admin.kriteria.update', props.data.id_kriteria) : route('admin.kriteria.store');

    if (isEdit) {
        form.put(url, {
            onSuccess: () => {
                const successMessage = page.props.flash?.success;
                if (successMessage) {
                    toast.success(successMessage, {
                        position: 'top-right',
                        duration: 3000,
                    });
                }
            },
            onError: () => {
                const errorMessage = page.props.flash?.error;
                if (errorMessage) {
                    toast.error(errorMessage, {
                        position: 'top-right',
                        duration: 3000,
                    });
                }
            },
        });
    } else {
        form.post(url, {
            onSuccess: () => {
                const successMessage = page.props.flash?.success;
                if (successMessage) {
                    toast.success(successMessage, {
                        position: 'top-right',
                        duration: 3000,
                    });
                    form.reset();
                }
            },
            onError: () => {
                const errorMessage = page.props.flash?.error;
                if (errorMessage) {
                    toast.error(errorMessage, {
                        position: 'top-right',
                        duration: 3000,
                    });
                }
            },
        });
    }
}
</script>

<template>
    <Head title="Tambah Data Kriteria" />
    <ConfirmDialog />
    <AppLayout :breadcrumbs="breadcrumbItems">
        <AdminLayout>
            <div class="space-y-6">
                <HeadingSmall title="Form Data Kriteria" description="Tambah/Edit Kriteria" />

                <form @submit.prevent="submit" class="max-w-md space-y-4">
                    <div class="space-y-2">
                        <Label for="nama">Nama Kriteria</Label>
                        <InputText
                            id="nama"
                            v-model="form.nama_kriteria"
                            type="text"
                            class="w-full"
                            :disabled="loading"
                            :invalid="!!form.errors.nama_kriteria"
                            placeholder="Masukkan nama kriteria"
                        />
                        <Message v-if="form.errors.nama_kriteria" severity="error" size="small" variant="simple">{{
                            form.errors.nama_kriteria
                        }}</Message>
                    </div>

                    <div class="space-y-2">
                        <Label for="bobot">Bobot</Label>
                        <InputText
                            id="bobot"
                            v-model="form.bobot"
                            rows="3"
                            :invalid="!!form.errors.bobot"
                            class="w-full"
                            :disabled="loading"
                            placeholder="Masukkan bobot kriteria"
                        />
                        <Message v-if="form.errors.bobot" severity="error" size="small" variant="simple">{{ form.errors.bobot }}</Message>
                    </div>

                    <div class="space-y-2">
                        <Label for="jenis">Tipe</Label>
                        <Select
                            v-model="form.jenis"
                            :options="tipeKriteria"
                            optionLabel="name"
                            optionValue="value"
                            :tabindex="1"
                            :disabled="form.processing"
                            :invalid="!!form.errors.jenis"
                            placeholder="Pilih tipe kriteria"
                            class="w-full"
                        />
                        <Message v-if="form.errors.jenis" severity="error" size="small" variant="simple">
                            {{ form.errors.jenis }}
                        </Message>
                    </div>

                    <Button type="submit" :label="props.data ? 'Update' : 'Simpan'" class="w-full" :disabled="form.processing">
                        <template #default>
                            <span v-if="form.processing" class="flex items-center justify-center gap-2">
                                <LoaderCircle class="h-4 w-4 animate-spin" />
                                Memproses...
                            </span>
                        </template>
                    </Button>
                </form>
            </div>
        </AdminLayout>
    </AppLayout>
</template>
