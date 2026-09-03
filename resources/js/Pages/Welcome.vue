<script setup>
import { Head, Link } from '@inertiajs/vue3';
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';

defineProps({
    name: String,
    quote: Object,
    auth: Object,
});

const features = [
    {
        title: 'Long-lasting Capacity',
        description: 'High-density Li-Po cells deliver all-day power with optimized discharge for consistent performance.',
        icon: 'M20 7H4a2 2 0 00-2 2v6a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zM4 9h16v6H4V9z',
    },
    {
        title: 'Safe & Efficient',
        description: 'Multi-layer safety (OVP/OCP/SCP) and temperature monitoring for reliable longevity.',
        icon: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
    },
];
</script>

<template>
    <Head :title="name" />

    <StorefrontLayout>
        <!-- Video Showcase -->
        <section class="relative flex h-[70vh] min-h-[420px] items-center justify-center overflow-hidden text-center text-white">
            <video
                class="absolute inset-0 h-full w-full object-cover"
                src="/images/bronx-battery.mp4"
                autoplay
                muted
                loop
                playsinline
            ></video>
            <div class="absolute inset-0 bg-black/50"></div>
            <div class="relative z-10 mx-auto max-w-3xl px-4">
                <p class="mb-4 text-xs font-bold uppercase tracking-[0.3em] text-gold">Proudly Made in Loveworld</p>
                <h2 class="text-3xl font-bold leading-tight sm:text-5xl">
                    Power, engineered with excellence.
                </h2>
            </div>
        </section>

        <!-- Hero -->
        <section class="relative flex min-h-[560px] items-center overflow-hidden bg-brand sm:min-h-[620px]">
            <div class="mx-auto grid w-full max-w-7xl grid-cols-1 items-center gap-10 px-4 py-16 sm:px-6 sm:py-24 lg:grid-cols-2 lg:px-8">
                <div class="z-10 flex flex-col gap-5 text-center text-white lg:text-left">
                    <p class="text-sm font-bold uppercase tracking-[0.3em] text-white/70">Bronx Batteries</p>
                    <h1 class="text-4xl font-bold leading-tight sm:text-5xl lg:text-6xl">
                        Power you can<br class="hidden sm:block"> depend on.
                    </h1>
                    <p class="inline-flex items-center gap-2 self-center rounded-full bg-white/10 px-4 py-1.5 text-xs font-bold uppercase tracking-widest text-white/90 lg:self-start">
                        Proudly Made in Loveworld
                    </p>
                    <p class="flex items-center justify-center gap-2 text-lg font-semibold text-white/90 lg:justify-start">
                        <img src="/images/espees_logo.png" alt="" class="h-6 w-6 object-contain">
                        Pack of 4 batteries — 1.5 Espees
                    </p>
                    <div class="mt-4 flex flex-col items-center gap-4 sm:flex-row sm:justify-center lg:justify-start">
                        <Link
                            :href="route('product.index')"
                            class="inline-flex h-12 w-full items-center justify-center rounded-lg bg-accent px-8 text-base font-bold text-black shadow-md transition hover:bg-accent-dark sm:w-auto"
                        >
                            Buy Now
                        </Link>
                        <a
                            href="#features"
                            class="inline-flex h-12 w-full items-center justify-center rounded-lg border-2 border-white/40 px-8 text-base font-bold text-white transition hover:bg-white/10 sm:w-auto"
                        >
                            Learn More
                        </a>
                    </div>
                </div>
                <div class="relative flex h-64 items-center justify-center sm:h-80 lg:h-[420px]">
                    <img
                        src="/images/battery_ring.png"
                        alt="Bronx Batteries — pack of 4"
                        class="max-h-full object-contain drop-shadow-2xl"
                        @error="(e) => e.target.src = 'https://placehold.co/400x600/0056D2/white?text=Bronx+Batteries'"
                    >
                </div>
            </div>
        </section>

        <!-- Battery Features Section -->
        <section id="features" class="overflow-hidden bg-white py-16 sm:py-24">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid items-center gap-12 lg:grid-cols-3">
                    <div class="space-y-4">
                        <p class="text-sm font-bold uppercase tracking-widest text-gray-400">Battery Features</p>
                        <h2 class="text-3xl font-bold leading-tight text-gray-900 sm:text-4xl md:text-5xl">
                            Power that lasts. Smart battery tech.
                        </h2>
                    </div>

                    <div class="relative order-first mb-12 flex justify-center lg:order-none lg:mb-0">
                        <div class="relative flex h-[300px] w-full max-w-[350px] items-center justify-center p-4 sm:p-8 md:h-[450px] md:max-w-[450px]">
                            <img src="/images/battery_ring.png" alt="Bronx Batteries" class="h-full w-full object-contain" @error="(e) => e.target.src = 'https://placehold.co/300x300/white/0056D2?text=Battery'">
                        </div>
                    </div>

                    <div class="space-y-10">
                        <div v-for="feature in features" :key="feature.title" class="flex gap-5">
                            <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center text-gray-900">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="feature.icon" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="mb-2 text-lg font-bold text-gray-900">{{ feature.title }}</h3>
                                <p class="text-sm leading-relaxed text-gray-500">{{ feature.description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </StorefrontLayout>
</template>
