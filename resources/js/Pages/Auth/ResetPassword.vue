<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';
import InputError from '@/Components/InputError.vue';
import Toast from '@/Components/Toast.vue';
import { ref } from 'vue';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const toast = ref(null);

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
        onSuccess: () => {
            toast.value.add('Password reset successfully!');
        },
        onError: () => {
            toast.value.add('Unable to reset password. Please check the form.', 'error');
        },
    });
};
</script>

<template>
    <Head title="Reset Password" />

    <div class="min-h-screen bg-white text-gray-900 font-sans flex flex-col pt-28">
        <Toast ref="toast" />
        <Header />

        <main class="flex-1 flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-white">
            <div class="w-full max-w-md space-y-8 bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                <div class="text-center">
                    <h2 class="text-3xl font-bold text-gray-900">Reset your password</h2>
                    <p class="mt-2 text-sm text-gray-500">
                        Enter a new password below to secure your account.
                    </p>
                </div>

                <form @submit.prevent="submit" class="mt-8 space-y-6">
                    <div class="space-y-4">
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

                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                            <input
                                id="password"
                                type="password"
                                v-model="form.password"
                                required
                                autocomplete="new-password"
                                class="mt-1 block w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#0056D2] focus:border-transparent outline-none transition placeholder-gray-400"
                                placeholder="Password"
                            />
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700">Confirm password</label>
                            <input
                                id="password_confirmation"
                                type="password"
                                v-model="form.password_confirmation"
                                required
                                autocomplete="new-password"
                                class="mt-1 block w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#0056D2] focus:border-transparent outline-none transition placeholder-gray-400"
                                placeholder="Confirm password"
                            />
                            <InputError class="mt-2" :message="form.errors.password_confirmation" />
                        </div>
                    </div>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-[#0056D2] hover:bg-[#0047AB] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0056D2] transition disabled:opacity-50"
                    >
                        Reset password
                    </button>
                </form>

                <div class="text-center text-sm text-gray-500">
                    Back to
                    <Link :href="route('login')" class="font-bold text-[#0056D2] hover:underline">
                        log in
                    </Link>
                </div>
            </div>
        </main>

        <Footer />
    </div>
</template>
