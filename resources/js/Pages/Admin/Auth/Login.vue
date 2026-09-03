<script setup>
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('admin.login.store'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Admin Login" />

    <main class="flex min-h-screen items-center justify-center bg-navy px-4 py-12 text-white">
        <form class="w-full max-w-md rounded-xl border border-white/10 bg-navy p-8 shadow-2xl" @submit.prevent="submit">
            <img src="/images/Bronx_Logo_2-removebg-preview.png" alt="Bronx Batteries" class="h-10 w-auto">
            <h1 class="mt-6 text-3xl font-black">Admin Sign In</h1>
            <p class="mt-2 text-sm text-white/60">Sign in to manage the store.</p>

            <div class="mt-8 space-y-5">
                <label class="block">
                    <span class="text-sm font-bold">Email</span>
                    <input
                        v-model="form.email"
                        type="email"
                        class="mt-2 h-12 w-full rounded-lg border border-white/20 bg-white px-4 text-gray-900 focus:border-brand focus:ring-brand"
                        autofocus
                    >
                    <span v-if="form.errors.email" class="mt-2 block text-sm text-red-400">{{ form.errors.email }}</span>
                </label>

                <label class="block">
                    <span class="text-sm font-bold">Password</span>
                    <input
                        v-model="form.password"
                        type="password"
                        class="mt-2 h-12 w-full rounded-lg border border-white/20 bg-white px-4 text-gray-900 focus:border-brand focus:ring-brand"
                    >
                    <span v-if="form.errors.password" class="mt-2 block text-sm text-red-400">{{ form.errors.password }}</span>
                </label>

                <label class="flex items-center gap-3 text-sm">
                    <input v-model="form.remember" type="checkbox" class="rounded border-white/30 text-brand focus:ring-brand">
                    Remember me
                </label>
            </div>

            <button
                type="submit"
                :disabled="form.processing"
                class="mt-8 h-12 w-full rounded-lg bg-brand font-black text-white transition hover:bg-brand-dark disabled:opacity-60"
            >
                Sign In
            </button>
        </form>
    </main>
</template>
