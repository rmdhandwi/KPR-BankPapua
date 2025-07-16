<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Column, DataTable } from 'primevue';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Perhitungan SAW',
        href: '/admin/perhitungan',
    },
];

const props = defineProps<{
    dataAwal: any[];
    normalisasi: any[];
    hasilAkhir: any[];
    kriteria: { id_kriteria: number; nama_kriteria: string }[];
    peringatan: string[];
    nilai_max: number;
    nilai_min: number;
    nilai_tengah: number;
}>();

const { dataAwal, normalisasi, hasilAkhir, kriteria, peringatan, nilai_max, nilai_min, nilai_tengah } = props;
</script>

<template>
    <Head title="Perhitungan SAW" />
    <AppLayout :breadcrumbs="breadcrumbItems">
        <div class="space-y-6 p-6">
            <h2 class="text-xl font-bold">Perhitungan SAW</h2>

            <Message v-if="peringatan.length" severity="warn">
                <ul class="ml-6 list-disc">
                    <li v-for="(msg, i) in peringatan" :key="i">{{ msg }}</li>
                </ul>
            </Message>

            <!-- Tabel Data Awal -->
            <Card>
                <template #title>Data Awal (Bobot Subkriteria)</template>
                <template #content>
                    <DataTable :value="dataAwal" class="p-datatable-sm" scrollable>
                        <Column field="nama_lengkap" header="Nama Nasabah" />
                        <Column v-for="k in kriteria" :key="k.id_kriteria" :field="k.id_kriteria" :header="k.nama_kriteria" />
                    </DataTable>
                </template>
            </Card>

            <!-- Tabel Normalisasi -->
            <Card>
                <template #title>Normalisasi</template>
                <template #content>
                    <DataTable :value="normalisasi" class="p-datatable-sm" scrollable>
                        <Column field="nama_lengkap" header="Nama Nasabah" />
                        <Column v-for="k in kriteria" :key="k.id_kriteria" :field="k.id_kriteria" :header="k.nama_kriteria" />
                    </DataTable>
                </template>
            </Card>

            <!-- Tabel Hasil Akhir -->
            <Card>
                <template #title>
                    <div class="flex w-full items-center justify-between">
                        <span>Hasil Akhir dan Peringkat</span>
                        <Tag :value="'Nilai Kelayakan: ' + nilai_tengah" severity="info" rounded />
                    </div>
                </template>

                <template #content>
                    <DataTable :value="hasilAkhir" class="p-datatable-sm" scrollable>
                        <Column field="nama_lengkap" header="Nama Nasabah" />
                        <Column field="nilai_akhir" header="Nilai Akhir" />
                        <Column header="Status">
                            <template #body="{ data }">
                                <Tag :value="data.status" :severity="data.status === 'Layak' ? 'success' : 'danger'" />
                            </template>
                        </Column>
                    </DataTable>
                </template>
            </Card>
        </div>
    </AppLayout>
</template>
