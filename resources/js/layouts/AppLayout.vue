<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="relative flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div
                class="pointer-events-none absolute inset-0 z-0 flex items-center justify-center"
                style="
                    background-image: url('/image/logo_transparant.png');
                    background-repeat: no-repeat;
                    background-position: center;
                    background-size: 100%;
                    opacity: 0.1;
                "
            ></div>
            <div class="z-10 space-y-6">
                <slot />
            </div>
        </div>
    </AppLayout>
</template>
