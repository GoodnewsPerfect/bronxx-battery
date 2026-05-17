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

    <main class="flex min-h-screen items-center justify-center bg-black px-4 py-12 text-white">
        <form class="w-full max-w-md rounded-xl border border-white bg-black p-8" @submit.prevent="submit">
            <h1 class="text-3xl font-black">Bronx Admin</h1>
            <p class="mt-2 text-sm text-white/60">Sign in to manage the store.</p>

            <div class="mt-8 space-y-5">
                <label class="block">
                    <span class="text-sm font-bold">Email</span>
                    <input
                        v-model="form.email"
                        type="email"
                        class="mt-2 h-12 w-full rounded-lg border border-white bg-white px-4 text-black focus:border-white focus:ring-white"
                        autofocus
                    >
                    <span v-if="form.errors.email" class="mt-2 block text-sm text-white">{{ form.errors.email }}</span>
                </label>

                <label class="block">
                    <span class="text-sm font-bold">Password</span>
                    <input
                        v-model="form.password"
                        type="password"
                        class="mt-2 h-12 w-full rounded-lg border border-white bg-white px-4 text-black focus:border-white focus:ring-white"
                    >
                    <span v-if="form.errors.password" class="mt-2 block text-sm text-white">{{ form.errors.password }}</span>
                </label>

                <label class="flex items-center gap-3 text-sm">
                    <input v-model="form.remember" type="checkbox" class="rounded border-white text-black focus:ring-white">
                    Remember me
                </label>
            </div>

            <button
                type="submit"
                :disabled="form.processing"
                class="mt-8 h-12 w-full rounded-lg bg-white font-black text-black transition hover:bg-white/90 disabled:opacity-60"
            >
                Sign In
            </button>
        </form>
    </main>
</template>
