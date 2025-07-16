import type { PageProps } from '@inertiajs/core';
import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
    match?: string[];
}

export interface SharedData extends PageProps {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    flash: {
        success?: string;
        error?: string;
        warning?: string;
        info?: string;
    };
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
    nasabah: Nasabah[];
    subkriteria: Subkriteria[];
    kriteria: Kriteria[];
    rumah: Rumah[];
    columns: string[];
}

export interface User {
    id: number;
    name: string;
    email: string;
    role: number;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface Nasabah {
    id_nasabah: number,
    id_rumah: number,
    nama_lengkap: string,
    no_ktp: number,
    no_kk: number,
    no_npwp: number,
    tempat_lahir: string,
    tgl_lahir: string,
    pekerjaan: string,
    nama_perusahan: string,
    alamat_perusahaan: string,
    email: string,
    penghasilan: string,
    no_tlp: string,
    kewarganegaraan: string,
    status_perkawinan: string,
    jml_tanggungan: string,
    riwayat_pinjol: string,
    riwayat_kredit: string,
    NPWP: string,
    KTP: string,
    surat_nikah: string,
    spt_tahunan: string,
    kartu_keluarga: string,
    kelengkapan_berkas
  [key: string]: string | number | boolean | null;
}

export interface Rumah {
    id_rumah: number;
    nama: string;
    tipe: string;
    luas_bangunan: number;
    luas_tanah: number;
    harga: number;
    karakteristik: string;
    created_at?: string;
    updated_at?: string;
}

export interface Kriteria{
    id_kriteria: number,
    nama_kriteria: string
    bobot: string
    jenis: string
}

export interface Subkriteria{
    id_subkriteria: number,
    nama_subkriteria: number,
    id_kriteria: number
    bobot: string
}


export type BreadcrumbItemType = BreadcrumbItem;
