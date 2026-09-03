<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    name: {
        type: String,
        default: 'Bronx',
    },
});
</script>

<template>
    <footer class="bg-navy text-white pt-16 sm:pt-20 pb-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 mb-12 sm:mb-20 text-center md:text-left">
                <div class="space-y-6">
                    <Link href="/" class="flex items-center justify-center md:justify-start">
                        <img src="/images/Bronx_Logo_2-removebg-preview.png" alt="Bronx Logo" class="h-10 w-auto">
                    </Link>
                    <p class="text-gray-400 text-sm">
                        Innovation in battery technology
                    </p>
                </div>

                <div>
                    <h4 class="font-bold mb-6 uppercase text-sm tracking-wider">Quick Links</h4>
                    <ul class="space-y-4 text-sm text-gray-400">
                        <li><Link href="/" class="hover:text-white transition">Home</Link></li>
                        <li><Link href="/product" class="hover:text-white transition">Product</Link></li>
                        <li><Link href="/about" class="hover:text-white transition">About Us</Link></li>
                        <li><Link href="/contact" class="hover:text-white transition">Contact Us</Link></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-center">
                <p class="text-sm text-gray-500">
                    &copy; {{ new Date().getFullYear() }} {{ name }}. All rights reserved.
                </p>
                <div class="flex flex-wrap items-center justify-center gap-5 md:justify-end">
                    <a
                        href="https://kingschat.online/user/bronx_electroni"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="text-gray-400 hover:text-white transition"
                        aria-label="KingsChat"
                    >
                        <img src="https://accounts.kingsch.at/favicon.ico" alt="KingsChat" class="w-10 h-10 object-contain">
                    </a>
                </div>
            </div>
        </div>
    </footer>
</template>
