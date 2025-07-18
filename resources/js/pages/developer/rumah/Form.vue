<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import Label from '@/components/ui/label/Label.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { SharedData } from '@/types';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { ConfirmDialog, useConfirm } from 'primevue';
import { ref } from 'vue';
import { useToast } from 'vue-toast-notification';

const breadcrumbItems = [
    {
        title: 'Form Data Rumah',
        href: '/developer/rumah/create',
    },
];

const props = defineProps<{
    data?: {
        id_rumah?: number;
        nama: string;
        tipe: string;
        luas_bangunan: number;
        luas_tanah: number;
        harga: number;
        karakteristik: string;
    };
}>();

const page = usePage<SharedData>();
const toast = useToast();
const confirm = useConfirm();
const loading = ref(false);

// Form setup
const form = useForm({
    id_rumah: props.data?.id_rumah ?? null,
    nama: props.data?.nama ?? '',
    tipe: props.data?.tipe ?? '',
    luas_bangunan: String(props.data?.luas_bangunan ?? ''),
    luas_tanah: String(props.data?.luas_tanah ?? ''),
    harga: String(props.data?.harga ?? ''),
    karakteristik: props.data?.karakteristik ?? '',
});

function submit() {
    if (props.data) {
        confirm.require({
            message: 'Apakah Anda yakin ingin mengubah data rumah ini?',
            header: 'Konfirmasi Update',
            icon: 'pi pi-exclamation-triangle',
            rejectProps: { label: 'Batal', severity: 'secondary' },
            acceptProps: { label: 'Ya' },
            accept: () => doSubmit(),
            reject: () => {
                toast.info('Batal mengubah data', { position: 'top-right' });
            },
        });
    } else {
        doSubmit();
    }
}

function handleSuccess() {
    const msg = page.props.flash?.success;
    if (msg) toast.success(msg, { position: 'top-right' });
    form.reset();
}

function handleError() {
    const msg = page.props.flash?.error ?? 'Terjadi kesalahan.';
    toast.error(msg, { position: 'top-right' });
}

function doSubmit() {
    const isEdit = !!props.data;
    const url = isEdit ? route('developer.rumah.update', props.data?.id_rumah) : route('developer.rumah.store');

    if (isEdit) {
        form.put(url, {
            onSuccess: handleSuccess,
            onError: handleError,
        });
    } else {
        form.post(url, {
            onSuccess: handleSuccess,
            onError: handleError,
        });
    }
}
</script>

<template>
    <ConfirmDialog />
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head :title="props.data ? 'Edit Rumah' : 'Tambah Rumah'" />
        <developerLayout>
            <div class="space-y-6 p-6">
                <HeadingSmall :title="props.data ? 'Edit Rumah' : 'Tambah Rumah'" description="Form untuk input atau update rumah." />

                <form @submit.prevent="submit()" class="max-w-xl space-y-5">
                    <div>
                        <Label class="mb-2" for="nama">Nama Rumah</Label>
                        <InputText :disabled="form.processing" v-model="form.nama" id="nama" class="w-full" :invalid="!!form.errors.nama" />
                        <Message variant="simple" size="small" v-if="form.errors.nama" severity="error">{{ form.errors.nama }}</Message>
                    </div>

                    <div>
                        <Label class="mb-2" for="tipe">Tipe</Label>
                        <InputText :disabled="form.processing" v-model="form.tipe" id="tipe" class="w-full" :invalid="!!form.errors.tipe" />
                        <Message variant="simple" size="small" v-if="form.errors.tipe" severity="error">{{ form.errors.tipe }}</Message>
                    </div>

                    <div>
                        <Label class="mb-2" for="luas_bangunan">Luas Bangunan (m²)</Label>
                        <InputText :disabled="form.processing" v-model="form.luas_bangunan" id="luas_bangunan" class="w-full" :invalid="!!form.errors.luas_bangunan" />
                        <Message variant="simple" size="small" v-if="form.errors.luas_bangunan" severity="error">{{
                            form.errors.luas_bangunan
                        }}</Message>
                    </div>

                    <div>
                        <Label class="mb-2" for="luas_tanah">Luas Tanah (m²)</Label>
                        <InputText :disabled="form.processing" v-model="form.luas_tanah" id="luas_tanah" class="w-full" :invalid="!!form.errors.luas_tanah" />
                        <Message variant="simple" size="small" v-if="form.errors.luas_tanah" severity="error">{{ form.errors.luas_tanah }}</Message>
                    </div>

                    <div>
                        <Label class="mb-2" for="harga">Harga (Rp)</Label>
                        <InputText :disabled="form.processing" v-model="form.harga" id="harga" class="w-full" :invalid="!!form.errors.harga" />
                        <Message variant="simple" size="small" v-if="form.errors.harga" severity="error">{{ form.errors.harga }}</Message>
                    </div>

                    <div>
                        <Label class="mb-2" for="karakteristik">Karakteristik</Label>
                        <Textarea :disabled="form.processing" v-model="form.karakteristik" id="karakteristik" rows="3" class="w-full" :invalid="!!form.errors.karakteristik" />
                        <Message variant="simple" size="small" v-if="form.errors.karakteristik" severity="error">{{
                            form.errors.karakteristik
                        }}</Message>
                    </div>

                    <div class="flex gap-2">
                        <Button type="button" label="Kembali" severity="secondary" class="w-full" as="a" :href="route('developer.rumah.index')" />
                        <Button type="submit" :label="props.data ? 'Update' : 'Simpan'" class="w-full" :loading="form.processing">
                            <template #default>
                                <span v-if="form.processing" class="flex items-center justify-center gap-2">
                                    <LoaderCircle class="h-4 w-4 animate-spin" />
                                    Memproses...
                                </span>
                            </template>
                        </Button>
                    </div>
                </form>
            </div>
        </developerLayout>
    </AppLayout>
</template>
