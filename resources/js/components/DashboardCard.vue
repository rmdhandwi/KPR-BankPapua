<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{
    icon: string;
    title: string;
    value: number | string;
    bg: string;
    text: string;
    href?: string;
}>();
</script>

<template>
    <Link v-if="href" :href="href" class="block">
        <div class="cursor-pointer rounded-lg p-4 shadow-md transition-all hover:shadow-lg" :class="[bg, text]">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium">{{ title }}</p>
                    <h2 class="text-xl font-bold">{{ value }}</h2>
                </div>
                <i :class="['text-3xl', icon]"></i>
            </div>
        </div>
    </Link>

    <!-- fallback jika tidak ada href -->
    <div v-else class="rounded-lg p-4 shadow-md transition-all hover:shadow-lg" :class="[bg, text]">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium">{{ title }}</p>
                <h2 class="text-xl font-bold">{{ value }}</h2>
            </div>
            <i :class="['text-3xl', icon]"></i>
        </div>
    </div>
</template>
