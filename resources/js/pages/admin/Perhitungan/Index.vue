<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Column, DataTable } from 'primevue';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Data Rumah',
        href: '/developer/rumah/',
    },
];

const props = defineProps<{
    dataAwal: any[];
    normalisasi: any[];
    hasilAkhir: any[];
    kriteria: { id_kriteria: number; nama_kriteria: string }[];
    peringatan: string[];
}>();

const { dataAwal, normalisasi, hasilAkhir, kriteria, peringatan } = props;
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
                <template #title>Hasil Akhir dan Peringkat</template>
                <template #content>
                    <DataTable :value="hasilAkhir" class="p-datatable-sm" scrollable>
                        <Column field="nama_lengkap" header="Nama Nasabah" />
                        <Column field="nilai_akhir" header="Nilai Akhir" />
                        <Column field="peringkat" header="Peringkat" />
                    </DataTable>
                </template>
            </Card>
        </div>
    </AppLayout>
</template>
