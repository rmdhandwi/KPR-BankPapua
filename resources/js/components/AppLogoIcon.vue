<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
    <img src="../../../public/image/logo_transparant.png" width="500" height="500" alt="Icon Logo" srcset="">
</template>
