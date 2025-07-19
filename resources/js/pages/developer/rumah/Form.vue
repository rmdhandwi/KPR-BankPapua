<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import Label from '@/components/ui/label/Label.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { SharedData } from '@/types';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { ConfirmDialog, useConfirm } from 'primevue';
import { ref, watch } from 'vue';
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

const tipeRumah = [
    {
        label: 'Tipe 21',
        value: 'Tipe 21',
        luas_bangunan: 21,
        luas_tanah: 60,
        harga: 150000000,
        karakteristik: '1 kamar tidur',
    },
    {
        label: 'Tipe 36',
        value: 'Tipe 36',
        luas_bangunan: 36,
        luas_tanah: 72,
        harga: 300000000,
        karakteristik: '2 kamar tidur, 1 kamar mandi, ruang tamu & dapur',
    },
    {
        label: 'Tipe 45',
        value: 'Tipe 45',
        luas_bangunan: 45,
        luas_tanah: 90,
        harga: 450000000,
        karakteristik: '2-3 kamar tidur',
    },
    {
        label: 'Tipe 60',
        value: 'Tipe 60',
        luas_bangunan: 60,
        luas_tanah: 120,
        harga: 450000000,
        karakteristik: '3 kamar tidur, 2 kamar mandi, bisa 1-2 lantai',
    },
    {
        label: 'Tipe 70',
        value: 'Tipe 70',
        luas_bangunan: 70,
        luas_tanah: 150,
        harga: 700000000,
        karakteristik: 'Rumah mewah, 2 lantai, fasilitas lengkap',
    },
];

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

watch(
    () => form.tipe,
    (newValue) => {
        const selected = tipeRumah.find(t => t.value === newValue);
        if (selected) {
            form.luas_bangunan = selected.luas_bangunan.toString();
            form.luas_tanah = selected.luas_tanah.toString();
            form.harga = selected.harga.toString();
            form.karakteristik = selected.karakteristik;
        }
    }
);

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
                        <Label class="mb-2" for="tipe">Tipe Rumah</Label>
                        <Dropdown
                            inputId="tipe"
                            class="w-full"
                            :options="tipeRumah"
                            v-model="form.tipe"
                            optionLabel="label"
                            optionValue="value"
                            :disabled="form.processing"
                            :invalid="!!form.errors.tipe"
                            placeholder="Pilih Tipe Rumah"
                        />
                        <Message variant="simple" size="small" v-if="form.errors.tipe" severity="error">{{ form.errors.tipe }}</Message>
                    </div>

                    <div>
                        <Label class="mb-2" for="luas_bangunan">Luas Bangunan (m²)</Label>
                        <InputText disabled v-model="form.luas_bangunan" id="luas_bangunan" class="w-full" :invalid="!!form.errors.luas_bangunan" />
                        <Message variant="simple" size="small" v-if="form.errors.luas_bangunan" severity="error">{{
                            form.errors.luas_bangunan
                        }}</Message>
                    </div>

                    <div>
                        <Label class="mb-2" for="luas_tanah">Luas Tanah (m²)</Label>
                        <InputText disabled v-model="form.luas_tanah" id="luas_tanah" class="w-full" :invalid="!!form.errors.luas_tanah" />
                        <Message variant="simple" size="small" v-if="form.errors.luas_tanah" severity="error">{{ form.errors.luas_tanah }}</Message>
                    </div>

                    <div>
                        <Label class="mb-2" for="harga">Harga (Rp)</Label>
                        <InputText disabled v-model="form.harga" id="harga" class="w-full" :invalid="!!form.errors.harga" />
                        <Message variant="simple" size="small" v-if="form.errors.harga" severity="error">{{ form.errors.harga }}</Message>
                    </div>

                    <div>
                        <Label class="mb-2" for="karakteristik">Karakteristik</Label>
                        <InputText
                            disabled
                            v-model="form.karakteristik"
                            id="karakteristik"
                            class="w-full"
                            :invalid="!!form.errors.karakteristik"
                        />
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
