<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import Header from '@/Components/Header.vue';
import Sidebar from '@/Components/Sidebar.vue';
import Footer from '@/Components/Footer.vue';

defineProps({
    name: String,
    cart: Object,
});

const isSidebarOpen = ref(false);
const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

const form = ref({
    name: '',
    email: '',
    subject: '',
    message: ''
});
</script>

<template>
    <Head title="Contact" />

    <div class="min-h-screen overflow-x-hidden bg-white text-gray-900 font-sans flex flex-col pt-28">
        <Sidebar :open="isSidebarOpen" />
        <Header :cart="cart" @toggle-sidebar="toggleSidebar" />

        <!-- Header Section -->
        <section class="py-12 bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Contact</h1>
                <nav class="flex text-sm text-gray-500">
                    <Link href="/" class="hover:text-gray-900">Home</Link>
                    <span class="mx-2">/</span>
                    <span class="text-gray-400">Contact</span>
                </nav>
            </div>
        </section>

        <!-- Contact Content Section -->
        <main class="flex-1 py-16 sm:py-24 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-10 lg:gap-16">
                    <!-- Left Side: Info -->
                    <div class="space-y-12">
                        <div class="space-y-6">
                            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 leading-tight">We'd love to hear from you</h2>
                            <p class="text-gray-600 leading-relaxed max-w-md">
                                Reach out for product questions, partnerships, or support.
                            </p>
                        </div>

                        <div class="space-y-8">
                            <div class="flex items-start">
                                <div class="w-10 h-10 bg-blue-50 rounded-full flex items-center justify-center text-[#0056D2] mr-4 shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-1">Office</h4>
                                    <p class="text-gray-500 text-sm">972 Sylvan Street South Angelina, NL</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="w-10 h-10 bg-blue-50 rounded-full flex items-center justify-center text-[#0056D2] mr-4 shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-1">Phone</h4>
                                    <p class="text-gray-500 text-sm">(633) 497-1888 / (062) 109-9222</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="w-10 h-10 bg-blue-50 rounded-full flex items-center justify-center text-[#0056D2] mr-4 shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-1">Email</h4>
                                    <p class="text-gray-500 text-sm">example@example.com</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side: Form -->
                    <div class="bg-white p-5 sm:p-8 md:p-10 border border-gray-100 rounded-2xl shadow-sm">
                        <form @submit.prevent class="space-y-6">
                            <div class="grid md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-sm font-semibold text-gray-700">Name</label>
                                    <input v-model="form.name" type="text" placeholder="Your full name" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#0056D2] focus:border-transparent outline-none transition placeholder-gray-400">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-semibold text-gray-700">Email</label>
                                    <input v-model="form.email" type="email" placeholder="you@example.com" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#0056D2] focus:border-transparent outline-none transition placeholder-gray-400">
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-700">Subject</label>
                                <input v-model="form.subject" type="text" placeholder="How can we help?" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#0056D2] focus:border-transparent outline-none transition placeholder-gray-400">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-700">Message</label>
                                <textarea v-model="form.message" rows="5" placeholder="Write your message..." class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#0056D2] focus:border-transparent outline-none transition resize-none placeholder-gray-400"></textarea>
                            </div>
                            <button class="bg-[#0056D2] hover:bg-[#0047AB] text-white px-8 py-3 rounded-lg font-bold transition shadow-md">
                                Send message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </main>

        <!-- Newsletter & Footer (Reused) -->
        <section class="py-16 bg-[#00D1FF] text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center md:text-left flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="w-full md:w-auto">
                    <h2 class="text-3xl font-bold mb-2">Subscribe Newsletter</h2>
                    <p class="text-white/90">Stay up to date with the latest news and offers</p>
                </div>
                <form class="w-full max-w-md flex flex-col sm:flex-row bg-white rounded-md overflow-hidden p-1 gap-1 sm:gap-0 shadow-lg">
                    <input type="email" placeholder="Your email address" class="min-w-0 flex-1 px-4 py-3 sm:py-2 text-gray-900 placeholder-gray-400 border-none focus:ring-0">
                    <button class="bg-[#FFD700] hover:bg-[#FFC400] text-black px-6 py-3 sm:py-2 rounded font-bold transition">Subscribe</button>
                </form>
            </div>
        </section>

        <Footer />
    </div>
</template>
