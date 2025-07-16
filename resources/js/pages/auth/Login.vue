<script setup lang="ts">
import { watch } from 'vue';
import { useForm, usePage, Head } from '@inertiajs/vue3';
import { useToast } from 'vue-toast-notification';
import { LoaderCircle } from 'lucide-vue-next';

import AuthBase from '@/layouts/AuthLayout.vue';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { SharedData } from '@/types';

const page = usePage<SharedData>();
const toast = useToast();

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const form = useForm({
    username: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

watch(
    () => page.props.flash?.error,
    (message) => {
        if (message) {
            toast.error(message, {
                position: 'top-right',
                duration: 3000,
            });
        }
    },
    { immediate: true }
);

watch(
    () => page.props.flash?.success,
    (message) => {
        if (message) {
            toast.success(message, {
                position: 'top-right',
                duration: 3000,
            });
        }
    },
    { immediate: true }
);
</script>

<template>
    <AuthBase
        title="Masuk ke KPR-BankPapua"
        description="Masukkan username dan kata sandi Anda untuk mengakses pengajuan kelayakan pemberian kredit pemilik rumah KPR."
    >
        <Head title="Masuk" />

        <!-- Status Message -->
        <div v-if="status" class="mb-4 text-center text-sm font-medium text-blue-600">
            {{ status }}
        </div>

        <!-- Login Form -->
        <form @submit.prevent="submit" class="flex flex-col gap-6 text-black">
            <div class="grid gap-6">
                <!-- Username -->
                <div class="grid gap-2">
                    <Label for="username">Username</Label>
                    <Input
                        id="username"
                        type="text"
                        autocomplete="username"
                        v-model="form.username"
                        :aria-invalid="!!form.errors.username"
                        :tabindex="1"
                        placeholder="Masukkan username"
                    />
                    <InputError :message="form.errors.username" />
                </div>

                <!-- Password -->
                <div class="grid gap-2">
                    <div class="flex items-center justify-between">
                        <Label for="password">Kata Sandi</Label>
                    </div>
                    <Input
                        id="password"
                        type="password"
                        autocomplete="current-password"
                        v-model="form.password"
                        :aria-invalid="!!form.errors.password"
                        :tabindex="2"
                        placeholder="••••••••"
                    />
                    <InputError :message="form.errors.password" />
                </div>

                <!-- Submit Button -->
                <Button
                    type="submit"
                    class="mt-4 w-full bg-[#2A388A] text-white hover:bg-[#56619e]"
                    :tabindex="4"
                    :disabled="form.processing"
                >
                    <span v-if="form.processing" class="flex items-center justify-center gap-2">
                        <LoaderCircle class="h-4 w-4 animate-spin" />
                        Memproses...
                    </span>
                    <span v-else>Masuk</span>
                </Button>
            </div>

            <!-- Sign up link -->
            <!-- <div class="text-center text-sm text-gray-600 dark:text-gray-400">
                Belum punya akun?
                <TextLink
                    :href="route('register')"
                    class="cursor-pointer underline underline-offset-4 transition-all hover:text-[#2A388A] hover:underline dark:hover:text-[#20639a]"
                    :tabindex="5"
                >
                    Daftar sekarang
                </TextLink>
            </div> -->
        </form>
    </AuthBase>
</template>
