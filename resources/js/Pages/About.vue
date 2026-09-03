<script setup>
import { Head, Link } from '@inertiajs/vue3';
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';

defineProps({
    name: String,
});

const valueCards = [
    {
        title: 'Secure by design',
        description: 'Best-in-class security practices and regular audits.',
        icon: 'M9 12l2 2 4-4m5.618-4.016A3.323 3.323 0 0010.605 8.9c5.135 0 9.35 4.215 9.35 9.35 0 .49-.039.971-.115 1.442M11 19l-7-7 7-7',
    },
    {
        title: 'Sustainably made',
        description: 'Materials and processes with the planet in mind.',
        icon: 'M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z',
    },
];
</script>

<template>
    <Head title="About" />

    <StorefrontLayout>
        <section class="border-b border-gray-100 bg-white py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h1 class="mb-2 text-3xl font-bold text-gray-900">About</h1>
                <nav class="flex text-sm text-gray-500">
                    <Link href="/" class="hover:text-gray-900">Home</Link>
                    <span class="mx-2">/</span>
                    <span class="text-gray-400">About</span>
                </nav>
            </div>
        </section>

        <!-- Product highlight -->
        <section class="bg-white py-16 sm:py-24">
            <div class="mx-auto grid max-w-7xl items-center gap-10 px-4 sm:px-6 lg:grid-cols-2 lg:gap-16 lg:px-8">
                <div>
                    <img src="/images/product1.jpg" alt="Bronx Batteries pack" class="w-full rounded-2xl object-contain shadow-md">
                </div>
                <div>
                    <p class="mb-3 text-xs font-bold uppercase tracking-[0.3em] text-brand">Made in Loveworld</p>
                    <h2 class="mb-6 text-3xl font-bold leading-tight text-gray-900 sm:mb-8 sm:text-4xl">Bronx Batteries — the best in the world</h2>
                    <p class="mb-6 leading-relaxed text-gray-600">
                        Bronx Batteries is proudly made in Loveworld, built to deliver dependable, long-lasting power you can trust every single time.
                    </p>
                </div>
            </div>
        </section>

        <!-- Value grid -->
        <section class="bg-white py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid gap-8 md:grid-cols-2">
                    <div v-for="card in valueCards" :key="card.title" class="rounded-xl border border-gray-100 p-8 shadow-sm transition-shadow hover:shadow-md">
                        <div class="mb-4 h-10 w-10 text-brand">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" :d="card.icon" />
                            </svg>
                        </div>
                        <h3 class="mb-2 text-lg font-bold">{{ card.title }}</h3>
                        <p class="text-sm leading-relaxed text-gray-500">{{ card.description }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Milestones Section (commented out for now)
        <section class="py-16 sm:py-24 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold mb-12">Milestones</h2>
                <div class="space-y-0 border-l-2 border-gray-100 ml-2">
                    <div v-for="milestone in milestones" :key="milestone.year" class="relative pl-8 pb-12">
                        <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-brand border-4 border-white"></div>
                        <p class="text-sm font-bold text-gray-400 mb-1">{{ milestone.year }}</p>
                        <h3 class="text-lg font-bold mb-2">{{ milestone.title }}</h3>
                        <p class="text-gray-500 text-sm leading-relaxed">{{ milestone.description }}</p>
                    </div>
                </div>
            </div>
        </section>
        -->

        <!-- Dark CTA -->
        <section class="bg-navy py-20 text-white sm:py-32">
            <div class="mx-auto grid max-w-7xl items-center gap-10 px-4 sm:px-6 lg:grid-cols-2 lg:gap-16 lg:px-8">
                <div>
                    <h2 class="mb-6 text-3xl font-bold leading-tight sm:text-4xl">You'll be happy to see our awesome features</h2>
                    <p class="mb-8 max-w-xl leading-relaxed text-gray-400">
                        Expression of modern scientific inquiry and exposition. Our approach follows a series of sprints to deliver consistent value while maintaining craftsmanship.
                    </p>
                    <Link :href="route('product.index')" class="flex items-center font-bold text-white transition hover:text-white/80">
                        Explore our products <span class="ml-2">→</span>
                    </Link>
                </div>
                <div class="flex justify-center lg:justify-end">
                    <img src="/images/product1.jpg" alt="Bronx Batteries pack" class="max-h-[260px] max-w-full rounded-2xl object-contain shadow-2xl sm:max-h-[300px]">
                </div>
            </div>
        </section>
    </StorefrontLayout>
</template>
