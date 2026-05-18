<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';
import Sidebar from '@/Components/Sidebar.vue';
import InputError from '@/Components/InputError.vue';

const user = usePage().props.auth.user;

const isSidebarOpen = ref(false);
const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

const form = useForm({
    name: user.name ?? '',
    email: user.email ?? '',
    phone_number: user.phone_number ?? '',
    birth_date: user.birth_date ?? '',
    country: user.country ?? ((user.country_code === '+234' || user.country_code === 'NG') ? 'Nigeria' : ''),
    state: user.state ?? '',
    city: user.city ?? '',
    username: user.username ?? '',
});

const saveProfile = () => {
    form.patch(route('profile.update'), {
        preserveScroll: true,
    });
};

const menuItems = [
    { name: 'My Profile', icon: 'user', active: true, href: route('dashboard'), method: 'get' },
    { name: 'Orders', icon: 'shopping-bag', active: false, href: route('orders.index'), method: 'get' },
    { name: 'Security', icon: 'lock-closed', active: false },
    { name: 'Logout', icon: 'logout', active: false, method: 'post', href: route('logout') },
];
</script>

<template>
    <Head title="My Account" />

    <div class="min-h-screen bg-[#F8FAFC] text-gray-900 font-sans flex flex-col pt-28">
        <Sidebar :open="isSidebarOpen" />
        <Header @toggle-sidebar="toggleSidebar" />

        <main class="flex-1 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row gap-8">
                    <!-- Left Sidebar Menu -->
                    <aside class="w-full md:w-60 shrink-0">
                        <nav class="space-y-2">
                            <template v-for="item in menuItems" :key="item.name">
                                <Link
                                    v-if="item.href"
                                    :href="item.href"
                                    :method="item.method || 'get'"
                                    :as="item.method === 'post' ? 'button' : 'a'"
                                    class="w-full flex items-center px-6 py-3.5 text-sm font-semibold rounded-xl transition-all duration-200"
                                    :class="item.active ? 'bg-[#0056D2] text-white shadow-lg shadow-blue-500/20' : 'text-gray-600 hover:bg-white hover:text-[#0056D2] hover:shadow-sm'"
                                >
                                    {{ item.name }}
                                </Link>
                                <button
                                    v-else
                                    class="w-full flex items-center px-6 py-3.5 text-sm font-semibold rounded-xl transition-all duration-200 text-left"
                                    :class="item.active ? 'bg-[#0056D2] text-white shadow-lg shadow-blue-500/20' : 'text-gray-600 hover:bg-white hover:text-[#0056D2] hover:shadow-sm'"
                                >
                                    {{ item.name }}
                                </button>
                            </template>
                        </nav>
                    </aside>

                    <!-- Main Content -->
                    <div class="flex-1 space-y-8">
                        <!-- Profile Information Section -->
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                            <div class="p-8">
                                <div class="flex items-center space-x-4 mb-8">
                                    <div class="w-10 h-10 bg-blue-50 rounded-full flex items-center justify-center text-[#0056D2]">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <h2 class="text-lg font-bold text-gray-900">Profile Information</h2>
                                </div>

                                <form @submit.prevent="saveProfile" class="space-y-6">
                                    <div class="grid md:grid-cols-2 gap-6">
                                        <div class="space-y-2">
                                            <label class="text-sm font-semibold text-gray-700 flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                                Full Name
                                            </label>
                                            <input
                                                v-model="form.name"
                                                type="text"
                                                autocomplete="name"
                                                class="w-full px-4 py-3 rounded-xl border border-gray-100 bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-[#0056D2] focus:border-transparent outline-none transition"
                                            >
                                            <InputError :message="form.errors.name" />
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-sm font-semibold text-gray-700 flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                                Email Address
                                            </label>
                                            <input
                                                v-model="form.email"
                                                type="email"
                                                autocomplete="email"
                                                class="w-full px-4 py-3 rounded-xl border border-gray-100 bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-[#0056D2] focus:border-transparent outline-none transition"
                                            >
                                            <InputError :message="form.errors.email" />
                                        </div>

                                        <div class="space-y-2">
                                            <label class="text-sm font-semibold text-gray-700 flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h2.3a1 1 0 01.95.68l1 3a1 1 0 01-.24 1.02l-1.45 1.45a16 16 0 006.29 6.29l1.45-1.45a1 1 0 011.02-.24l3 1a1 1 0 01.68.95V19a2 2 0 01-2 2h-1C9.82 21 3 14.18 3 6V5z" />
                                                </svg>
                                                Phone
                                            </label>
                                            <input
                                                v-model="form.phone_number"
                                                type="tel"
                                                autocomplete="tel"
                                                class="w-full px-4 py-3 rounded-xl border border-gray-100 bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-[#0056D2] focus:border-transparent outline-none transition"
                                            >
                                            <InputError :message="form.errors.phone_number" />
                                        </div>

                                        <div class="space-y-2">
                                            <label class="text-sm font-semibold text-gray-700 flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M5 11h14M7 21h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                Birthday
                                            </label>
                                            <input
                                                v-model="form.birth_date"
                                                type="date"
                                                class="w-full px-4 py-3 rounded-xl border border-gray-100 bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-[#0056D2] focus:border-transparent outline-none transition"
                                            >
                                            <InputError :message="form.errors.birth_date" />
                                        </div>

                                        <div class="space-y-2">
                                            <label class="text-sm font-semibold text-gray-700 flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v1.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Country
                                            </label>
                                            <input
                                                v-model="form.country"
                                                type="text"
                                                autocomplete="country-name"
                                                class="w-full px-4 py-3 rounded-xl border border-gray-100 bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-[#0056D2] focus:border-transparent outline-none transition"
                                            >
                                            <InputError :message="form.errors.country" />
                                        </div>

                                        <div class="space-y-2">
                                            <label class="text-sm font-semibold text-gray-700 flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.45-2.72A1 1 0 013 16.38V5.62a1 1 0 011.45-.9L9 7m0 13l6-3m-6 3V7m6 10l4.55 2.28A1 1 0 0021 18.38V7.62a1 1 0 00-.55-.9L15 4m0 13V4m0 0L9 7" />
                                                </svg>
                                                State / Region
                                            </label>
                                            <input
                                                v-model="form.state"
                                                type="text"
                                                autocomplete="address-level1"
                                                class="w-full px-4 py-3 rounded-xl border border-gray-100 bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-[#0056D2] focus:border-transparent outline-none transition"
                                            >
                                            <InputError :message="form.errors.state" />
                                        </div>

                                        <div class="space-y-2">
                                            <label class="text-sm font-semibold text-gray-700 flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                City
                                            </label>
                                            <input
                                                v-model="form.city"
                                                type="text"
                                                autocomplete="address-level2"
                                                class="w-full px-4 py-3 rounded-xl border border-gray-100 bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-[#0056D2] focus:border-transparent outline-none transition"
                                            >
                                            <InputError :message="form.errors.city" />
                                        </div>

                                        <div class="space-y-2">
                                            <label class="text-sm font-semibold text-gray-700 flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-3.5 7.1" />
                                                </svg>
                                                KingsChat
                                            </label>
                                            <div class="flex items-center rounded-xl border border-gray-100 bg-gray-50/50 px-4 py-3 focus-within:bg-white focus-within:ring-2 focus-within:ring-[#0056D2]">
                                                <span class="text-gray-500">@</span>
                                                <input
                                                    v-model="form.username"
                                                    type="text"
                                                    autocomplete="username"
                                                    class="min-w-0 flex-1 border-0 bg-transparent p-0 pl-0.5 outline-none focus:ring-0"
                                                >
                                            </div>
                                            <InputError :message="form.errors.username" />
                                        </div>
                                    </div>

                                    <div class="flex items-center space-x-4">
                                        <button
                                            type="submit"
                                            :disabled="form.processing"
                                            class="bg-[#0056D2] hover:bg-[#0047AB] text-white px-6 py-2.5 rounded-lg font-bold transition shadow-md disabled:opacity-50"
                                        >
                                            Save Changes
                                        </button>
                                        <Transition
                                            enter-active-class="transition ease-in-out"
                                            enter-from-class="opacity-0"
                                            leave-active-class="transition ease-in-out"
                                            leave-to-class="opacity-0"
                                        >
                                            <p v-if="form.recentlySuccessful" class="text-sm text-green-600 font-medium">Saved successfully!</p>
                                        </Transition>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Delivery Address Section -->
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                            <div class="p-8">
                                <div class="flex items-center space-x-4 mb-12">
                                    <div class="w-10 h-10 bg-blue-50 rounded-full flex items-center justify-center text-[#0056D2]">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <h2 class="text-lg font-bold text-gray-900">Delivery Address</h2>
                                </div>

                                <div class="flex flex-col items-center justify-center py-12 text-center">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-gray-300 mb-4">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-gray-900 font-bold mb-1">No delivery address set</h3>
                                    <p class="text-gray-500 text-sm mb-8">Add a delivery address to speed up checkout</p>
                                    <button class="px-6 py-2.5 rounded-lg font-bold border border-gray-200 hover:bg-gray-50 transition">
                                        Add Delivery Address
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <Footer />
    </div>
</template>
