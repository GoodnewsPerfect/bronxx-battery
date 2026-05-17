<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';
import InputError from '@/Components/InputError.vue';
import Toast from '@/Components/Toast.vue';
import { ref } from 'vue';

defineProps({
    status: {
        type: String,
    },
});

const toast = ref(null);
const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'), {
        onSuccess: () => {
            toast.value.add('Password reset link sent!');
        },
        onError: () => {
            toast.value.add('Unable to send reset link. Please check your email.', 'error');
        },
    });
};
</script>

<template>
    <Head title="Forgot Password" />

    <div class="min-h-screen bg-white text-gray-900 font-sans flex flex-col pt-28">
        <Toast ref="toast" />
        <Header />

        <main class="flex-1 flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-white">
            <div class="w-full max-w-md space-y-8 bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                <div class="text-center">
                    <h2 class="text-3xl font-bold text-gray-900">Forgot your password?</h2>
                    <p class="mt-2 text-sm text-gray-500">
                        Enter your email and we will send you a password reset link.
                    </p>
                </div>

                <div v-if="status" class="text-sm font-medium text-green-600 text-center">
                    {{ status }}
                </div>

                <form @submit.prevent="submit" class="mt-8 space-y-6">
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700">Email address</label>
                        <input
                            id="email"
                            type="email"
                            v-model="form.email"
                            required
                            autofocus
                            autocomplete="username"
                            class="mt-1 block w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#0056D2] focus:border-transparent outline-none transition placeholder-gray-400"
                            placeholder="email@example.com"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-[#0056D2] hover:bg-[#0047AB] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0056D2] transition disabled:opacity-50"
                    >
                        Email password reset link
                    </button>
                </form>

                <div class="text-center text-sm text-gray-500">
                    Remembered your password?
                    <Link :href="route('login')" class="font-bold text-[#0056D2] hover:underline">
                        Log in
                    </Link>
                </div>
            </div>
        </main>

        <Footer />
    </div>
</template>
