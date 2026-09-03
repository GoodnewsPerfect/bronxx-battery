<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';

defineProps({
    name: String,
});

const form = ref({
    name: '',
    email: '',
    subject: '',
    message: '',
});
</script>

<template>
    <Head title="Contact" />

    <StorefrontLayout>
        <section class="border-b border-gray-100 bg-white py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h1 class="mb-2 text-3xl font-bold text-gray-900">Contact</h1>
                <nav class="flex text-sm text-gray-500">
                    <Link href="/" class="hover:text-gray-900">Home</Link>
                    <span class="mx-2">/</span>
                    <span class="text-gray-400">Contact</span>
                </nav>
            </div>
        </section>

        <section class="flex-1 bg-white py-16 sm:py-24">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid gap-10 lg:grid-cols-2 lg:gap-16">
                    <!-- Info -->
                    <div class="space-y-12">
                        <div class="space-y-6">
                            <h2 class="text-3xl font-bold leading-tight text-gray-900 sm:text-4xl">We'd love to hear from you</h2>
                            <p class="max-w-md leading-relaxed text-gray-600">
                                Reach out for product questions, partnerships, or support.
                            </p>
                        </div>

                        <div class="space-y-8">
                            <div class="flex items-start">
                                <div class="mr-4 flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-brand/10 text-brand">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="mb-1 font-bold text-gray-900">Office</h4>
                                    <p class="text-sm text-gray-500">Aseese, Nigeria</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="mr-4 flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden rounded-full bg-brand/10">
                                    <img src="https://accounts.kingsch.at/favicon.ico" alt="KingsChat" class="h-6 w-6 object-contain">
                                </div>
                                <div>
                                    <h4 class="mb-1 font-bold text-gray-900">KingsChat</h4>
                                    <a
                                        href="https://kingschat.online/user/bronx_electroni"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="text-sm text-gray-500 hover:text-brand"
                                    >
                                        @bronx_electroni
                                    </a>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="mr-4 flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-brand/10 text-brand">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="mb-1 font-bold text-gray-900">Email</h4>
                                    <p class="text-sm text-gray-500">sales@bronxbatteries.org</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form -->
                    <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-8 md:p-10">
                        <form @submit.prevent class="space-y-6">
                            <div class="grid gap-6 md:grid-cols-2">
                                <div class="space-y-2">
                                    <label for="contact-name" class="text-sm font-semibold text-gray-700">Name</label>
                                    <input id="contact-name" v-model="form.name" type="text" placeholder="Your full name" class="w-full rounded-xl border border-gray-200 px-4 py-3 placeholder-gray-400 outline-none transition focus:border-transparent focus:ring-2 focus:ring-brand">
                                </div>
                                <div class="space-y-2">
                                    <label for="contact-email" class="text-sm font-semibold text-gray-700">Email</label>
                                    <input id="contact-email" v-model="form.email" type="email" placeholder="you@example.com" class="w-full rounded-xl border border-gray-200 px-4 py-3 placeholder-gray-400 outline-none transition focus:border-transparent focus:ring-2 focus:ring-brand">
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label for="contact-subject" class="text-sm font-semibold text-gray-700">Subject</label>
                                <input id="contact-subject" v-model="form.subject" type="text" placeholder="How can we help?" class="w-full rounded-xl border border-gray-200 px-4 py-3 placeholder-gray-400 outline-none transition focus:border-transparent focus:ring-2 focus:ring-brand">
                            </div>
                            <div class="space-y-2">
                                <label for="contact-message" class="text-sm font-semibold text-gray-700">Message</label>
                                <textarea id="contact-message" v-model="form.message" rows="5" placeholder="Write your message..." class="w-full resize-none rounded-xl border border-gray-200 px-4 py-3 placeholder-gray-400 outline-none transition focus:border-transparent focus:ring-2 focus:ring-brand"></textarea>
                            </div>
                            <button class="rounded-lg bg-brand px-8 py-3 font-bold text-white shadow-md transition hover:bg-brand-dark">
                                Send message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </StorefrontLayout>
</template>
