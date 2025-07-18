<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import Label from '@/components/ui/label/Label.vue';
import AdminLayout from '@/layouts/admin/Kriteria.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Kriteria, SharedData, type BreadcrumbItem } from '@/types';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { ConfirmDialog, Select, useConfirm } from 'primevue';
import { ref } from 'vue';
import { useToast } from 'vue-toast-notification';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Form Sub Kriteria',
        href: '/subkriteria/create',
    },
];

const props = defineProps<{
    data?: {
        id_subkriteria?: number;
        id_kriteria: string;
        nama_subkriteria: string;
        bobot: string;
    };
}>();

const page = usePage<SharedData>();
const kriteria = ref<Kriteria[]>(page.props.kriteria || []);
const confirm = useConfirm();
const toast = useToast();

// Setup form dengan default value dari props.data jika ada (edit mode)
const form = useForm({
    id_subkriteria: props.data?.id_subkriteria ?? null,
    id_kriteria: props.data?.id_kriteria ?? null,
    nama_subkriteria: props.data?.nama_subkriteria ?? '',
    bobot: props.data?.bobot ?? '',
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

    const url = isEdit ? route('admin.subkriteria.update', props.data.id_subkriteria) : route('admin.subkriteria.store');

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
    <Head title="Tambah Data Sub Kriteria" />
    <ConfirmDialog />
    <AppLayout :breadcrumbs="breadcrumbItems">
        <AdminLayout>
            <div class="space-y-6">
                <HeadingSmall title="Form Data Sub Kriteria" description="Tambah/Edit Sub Kriteria" />

                <form @submit.prevent="submit" class="max-w-md space-y-4">
                    <div class="space-y-2">
                        <Label for="nama">Kriteria</Label>
                        <Select
                            v-model="form.id_kriteria"
                            :options="kriteria"
                            optionLabel="nama_kriteria"
                            optionValue="id_kriteria"
                            :tabindex="1"
                            :invalid="!!form.errors.id_kriteria"
                            placeholder="Pilih kriteria"
                            :disabled="form.processing"
                            class="w-full"
                        />
                        <Message v-if="form.errors.id_kriteria" severity="error" size="small" variant="simple">
                            {{ form.errors.id_kriteria }}
                        </Message>
                    </div>

                    <div class="space-y-2">
                        <Label for="nama">Nama Sub Kriteria</Label>
                        <InputText
                            id="nama"
                            v-model="form.nama_subkriteria"
                            type="text"
                            class="w-full"
                            :disabled="form.processing"
                            :invalid="!!form.errors.nama_subkriteria"
                            placeholder="Masukkan nama sub kriteria"
                        />
                        <Message v-if="form.errors.nama_subkriteria" severity="error" size="small" variant="simple">{{
                            form.errors.nama_subkriteria
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
                            :disabled="form.processing"
                            placeholder="Masukkan bobot sub kriteria"
                        />
                        <Message v-if="form.errors.bobot" severity="error" size="small" variant="simple">{{ form.errors.bobot }}</Message>
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
