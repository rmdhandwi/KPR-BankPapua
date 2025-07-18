<script setup lang="ts">
import type { Laporan, SharedData } from '@/types';
import { Head, router, usePage } from '@inertiajs/vue3';
import { onBeforeUnmount, onMounted, ref } from 'vue';

const page = usePage<SharedData>();
const laporan = ref<Laporan[]>([]);
const tanggal = page.props.tanggal as string;

// Data awal
onMounted(() => {
    laporan.value = page.props.laporan ?? [];
    setTimeout(() => {
        window.print();
    }, 500);
    window.addEventListener('afterprint', handleAfterPrint);
});

// Format tanggal ID
function formatTanggal(tanggal: string): string {
    return new Date(tanggal).toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
    });
}

function handleAfterPrint() {
    router.visit('/admin/laporan');
}
// Pastikan hapus listener saat keluar
onBeforeUnmount(() => {
    window.removeEventListener('afterprint', handleAfterPrint);
});
</script>

<template>
    <Head title="Laporan Print" />
    <div class="p-8 text-gray-900 print:bg-white print:text-black">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between border-b pb-4">
            <div class="flex items-center gap-4">
                <img src="../../../../../public/image/logo_transparant.png" alt="Logo" class="h-16" />
                <div>
                    <h1 class="text-2xl font-bold uppercase">Laporan Hasil Kelayakan KPR</h1>
                    <p class="text-sm">
                        <i>Tanggal Cetak: {{ formatTanggal(tanggal) }}</i>
                    </p>
                </div>
            </div>
        </div>

        <!-- Tabel -->
        <table class="w-full border border-gray-400 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border p-2">No</th>
                    <th class="border p-2">Nama Nasabah</th>
                    <th class="border p-2">Email</th>
                    <th class="border p-2">Rumah</th>
                    <th class="border p-2">Tipe</th>
                    <th class="border p-2">Skor Akhir</th>
                    <th class="border p-2">Status</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in laporan" :key="item.id_perhitungan">
                    <td class="border p-2 text-center">{{ index + 1 }}</td>
                    <td class="border p-2">{{ item.nama_lengkap }}</td>
                    <td class="border p-2">{{ item.email }}</td>
                    <td class="border p-2">{{ item.nama }}</td>
                    <td class="border p-2 text-center">{{ item.tipe }}</td>
                    <td class="border p-2 text-center">{{ item.skor_akhir }}</td>
                    <td class="border p-2 text-center">
                        <Tag
                            :value="item.status_kelayakan"
                            :severity="item.status_kelayakan === 'Layak' ? 'success' : 'danger'"
                            :icon="item.status_kelayakan === 'Layak' ? 'pi pi-check-circle' : 'pi pi-times-circle'"
                            class="capitalize"
                        />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<style>
@media print {
    @page {
        size: A4 portrait;
        margin: 20mm;
    }

    body {
        -webkit-print-color-adjust: exact;
    }
}
</style>
