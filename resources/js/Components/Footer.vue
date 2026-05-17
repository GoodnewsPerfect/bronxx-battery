<script setup>
defineProps({
    name: {
        type: String,
        default: 'Bronx',
    },
});
</script>

<template>
    <footer class="bg-[#0B1222] text-white">
        <div class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
            <div class="grid gap-12 md:grid-cols-3">
                <div>
                    <div class="mb-6 text-4xl font-bold tracking-tight">
                        Bron<span class="text-[#FF4D4D]">x</span>
                    </div>
                    <p class="text-lg text-[#C8BEDD]">Innovation in battery technology</p>
                </div>

                <div>
                    <h3 class="mb-8 text-xl font-bold uppercase tracking-wide">Quick Links</h3>
                    <nav class="space-y-4 text-lg text-[#C8BEDD]">
                        <a href="/" class="block hover:text-white">Home</a>
                        <a href="/product" class="block hover:text-white">Product</a>
                        <a href="/about" class="block hover:text-white">About Us</a>
                        <a href="/contact" class="block hover:text-white">Contact Us</a>
                    </nav>
                </div>

                <div>
                    <h3 class="mb-8 text-xl font-bold uppercase tracking-wide">Opening Time</h3>
                    <div class="space-y-4 text-lg text-[#C8BEDD]">
                        <p>Monday - Friday: 9am - 5pm</p>
                        <p>Saturday: 10am - 3pm</p>
                        <p>Sunday: Closed</p>
                    </div>
                </div>
            </div>

            <div class="mt-16 border-t border-[#2456C6] pt-8 text-center text-sm text-[#C8BEDD]">
                &copy; {{ new Date().getFullYear() }} {{ name }}. All rights reserved.
            </div>
        </div>
    </footer>
</template>
