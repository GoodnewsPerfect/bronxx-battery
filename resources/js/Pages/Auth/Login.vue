<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';
import InputError from '@/Components/InputError.vue';
import Checkbox from '@/Components/Checkbox.vue';
import Toast from '@/Components/Toast.vue';
import { ref } from 'vue';

defineProps({
    status: {
        type: String,
    },
    kingschat_auth_url: {
        type: String,
    },
});

const toast = ref(null);
const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
        onSuccess: () => {
            toast.value.add('Logged in successfully!');
        },
        onError: () => {
            toast.value.add('Login failed. Please check your credentials.', 'error');
        }
    });
};
</script>

<template>
    <Head title="Log in" />

    <div class="min-h-screen bg-white text-gray-900 font-sans flex flex-col pt-28">
        <Toast ref="toast" />
        <Header />

        <main class="flex-1 flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-white">
            <div class="w-full max-w-md space-y-8 bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                <div class="text-center">
                    <h2 class="text-3xl font-bold text-gray-900">Log in to your account</h2>
                    <p class="mt-2 text-sm text-gray-500">Enter your email and password below to log in</p>
                </div>

                <div v-if="status" class="mb-4 text-sm font-medium text-green-600 text-center">
                    {{ status }}
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
                                autocomplete="current-password"
                                class="mt-1 block w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#0056D2] focus:border-transparent outline-none transition placeholder-gray-400"
                                placeholder="Password"
                            />
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <Checkbox id="remember" name="remember" v-model:checked="form.remember" />
                            <label for="remember" class="ml-2 text-sm text-gray-600">Remember me</label>
                        </div>
                        <Link
                            :href="route('password.request')"
                            class="text-sm font-medium text-[#0056D2] hover:underline"
                        >
                            Forgot password?
                        </Link>
                    </div>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-[#0056D2] hover:bg-[#0047AB] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0056D2] transition disabled:opacity-50"
                    >
                        Log in
                    </button>
                </form>

                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500 uppercase tracking-widest text-xs font-bold">Or</span>
                    </div>
                </div>

                <div class="space-y-4">
                    <a
                        :href="kingschat_auth_url"
                        class="w-full flex items-center justify-center px-4 py-3 border border-gray-200 rounded-xl shadow-sm text-sm font-bold text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0056D2] transition"
                    >
                        <img src="https://accounts.kingsch.at/favicon.ico" class="w-5 h-5 mr-3" alt="KingsChat">
                        Continue with KingsChat
                    </a>
                </div>

                <div class="text-center text-sm text-gray-500">
                    Don't have an account?
                    <Link :href="route('register')" class="font-bold text-[#0056D2] hover:underline">
                        Sign up
                    </Link>
                </div>
            </div>
        </main>

        <Footer />
    </div>
</template>
