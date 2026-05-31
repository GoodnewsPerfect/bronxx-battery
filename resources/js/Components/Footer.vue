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
    <footer class="bg-[#0A1221] text-white pt-16 sm:pt-20 pb-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-12 mb-12 sm:mb-20 text-center md:text-left">
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

                <div>
                    <h4 class="font-bold mb-6 uppercase text-sm tracking-wider">Opening Time</h4>
                    <ul class="space-y-4 text-sm text-gray-400">
                        <li>Monday - Friday: 9am - 5pm</li>
                        <li>Saturday: 10am - 3pm</li>
                        <li>Sunday: Closed</li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-center">
                <p class="text-sm text-gray-500">
                    &copy; {{ new Date().getFullYear() }} {{ name }}. All rights reserved.
                </p>
                <div class="flex flex-wrap items-center justify-center gap-5 md:justify-end">
                    <a
                        href="https://kingsch.at/"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="text-gray-400 hover:text-white transition"
                        aria-label="KingsChat"
                    >
                        <img src="https://accounts.kingsch.at/favicon.ico" alt="KingsChat" class="w-10 h-10 object-contain">
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition" aria-label="Facebook">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v1.385z"/></svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition" aria-label="Twitter">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition" aria-label="Instagram">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition" aria-label="LinkedIn">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.238 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>
</template>
