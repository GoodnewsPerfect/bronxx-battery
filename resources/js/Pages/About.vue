<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import Header from '@/Components/Header.vue';
import Sidebar from '@/Components/Sidebar.vue';

defineProps({
    name: String,
    cart: Object,
});

const isSidebarOpen = ref(false);
const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

const valueCards = [
    {
        title: 'Secure by design',
        description: 'Best-in-class security practices and regular audits.',
        icon: 'M9 12l2 2 4-4m5.618-4.016A3.323 3.323 0 0010.605 8.9c5.135 0 9.35 4.215 9.35 9.35 0 .49-.039.971-.115 1.442M11 19l-7-7 7-7'
    },
    {
        title: 'Sustainably made',
        description: 'Materials and processes with the planet in mind.',
        icon: 'M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z'
    },
    {
        title: 'Award-winning support',
        description: 'Humans that care, available when you need them.',
        icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'
    },
    {
        title: 'Global footprint',
        description: 'Products used in 40+ countries and growing.',
        icon: 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
    }
];

const milestones = [
    { year: '2019', title: 'Founded', description: 'Started with a small team and a big vision.' },
    { year: '2021', title: 'First 10k customers', description: 'Reached product-market fit and scaled support.' },
    { year: '2024', title: 'Sustainability pledge', description: 'Offset operations and improved packaging.' }
];

const beliefs = [
    { title: 'Customer first', description: 'We listen deeply and iterate fast to exceed expectations.' },
    { title: 'Craft matters', description: 'Small details compound into remarkable experiences.' },
    { title: 'Responsible manufacturing', description: 'Supply chains audited and continuously improved.' }
];
</script>

<template>
    <Head title="About" />

    <div class="min-h-screen bg-white text-gray-900 font-sans flex flex-col pt-28">
        <Sidebar :open="isSidebarOpen" />
        <Header :cart="cart" @toggle-sidebar="toggleSidebar" />

        <!-- 1. Header Section -->
        <section class="py-12 bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">About</h1>
                <nav class="flex text-sm text-gray-500">
                    <Link href="/" class="hover:text-gray-900">Home</Link>
                    <span class="mx-2">/</span>
                    <span class="text-gray-400">About</span>
                </nav>
            </div>
        </section>

        <!-- 2. Beautiful Products Section -->
        <section class="py-24 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <div>
                        <img src="https://bronx.peachy.ng/build/assets/battery-specs.png" alt="Bronx Batteries" class="w-full object-contain" @error="(e) => e.target.src = '/images/bronx_spec.png'">
                    </div>
                    <div>
                        <h2 class="text-4xl font-bold text-gray-900 mb-8 leading-tight">We have created beautiful products for you</h2>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            A streamlined cloud solution. User generated content in real-time will have multiple touchpoints that evolve towards a unified experience across devices.
                        </p>
                        <p class="text-gray-600 leading-relaxed">
                            Our design system enables rapid iteration while maintaining quality and performance.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 3. Value Grid Section -->
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-2 gap-8">
                    <div v-for="card in valueCards" :key="card.title" class="p-8 border border-gray-100 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                        <div class="w-10 h-10 text-[#0056D2] mb-4">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" :d="card.icon" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold mb-2">{{ card.title }}</h3>
                        <p class="text-gray-500 text-sm leading-relaxed">{{ card.description }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 4. Stats Section -->
        <section class="py-24 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-3 gap-12 text-center">
                    <div>
                        <p class="text-5xl font-bold text-[#0056D2] mb-2">2346</p>
                        <p class="text-xs text-gray-400 uppercase tracking-widest font-bold">Employees from around the world</p>
                    </div>
                    <div>
                        <p class="text-5xl font-bold text-[#0056D2] mb-2">345+</p>
                        <p class="text-xs text-gray-400 uppercase tracking-widest font-bold">We care about our investors</p>
                    </div>
                    <div>
                        <p class="text-5xl font-bold text-[#0056D2] mb-2">26+</p>
                        <p class="text-xs text-gray-400 uppercase tracking-widest font-bold">People are our biggest capital</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 5. Milestones Section -->
        <section class="py-24 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold mb-12">Milestones</h2>
                <div class="space-y-0 border-l-2 border-gray-100 ml-2">
                    <div v-for="milestone in milestones" :key="milestone.year" class="relative pl-8 pb-12">
                        <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-[#0056D2] border-4 border-white"></div>
                        <p class="text-sm font-bold text-gray-400 mb-1">{{ milestone.year }}</p>
                        <h3 class="text-lg font-bold mb-2">{{ milestone.title }}</h3>
                        <p class="text-gray-500 text-sm leading-relaxed">{{ milestone.description }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 6. What we believe Section -->
        <section class="py-24 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold mb-12">What we believe</h2>
                <div class="grid md:grid-cols-3 gap-8">
                    <div v-for="belief in beliefs" :key="belief.title" class="p-8 bg-[#F8F9FA] rounded-xl border border-gray-100">
                        <h3 class="text-lg font-bold mb-4">{{ belief.title }}</h3>
                        <p class="text-gray-500 text-sm leading-relaxed">{{ belief.description }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 7. Dark CTA Section -->
        <section class="py-32 bg-[#0A1221] text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <div>
                        <h2 class="text-4xl font-bold mb-6 leading-tight">You'll be happy to see our awesome features</h2>
                        <p class="text-gray-400 mb-8 leading-relaxed max-w-xl">
                            Expression of modern scientific inquiry and exposition. Our approach follows a series of sprints to deliver consistent value while maintaining craftsmanship.
                        </p>
                        <Link :href="route('product.index')" class="text-white font-bold flex items-center hover:text-white/80 transition">
                            Explore our products <span class="ml-2">→</span>
                        </Link>
                    </div>
                    <div class="flex justify-end">
                        <img src="https://bronx.peachy.ng/build/assets/hero-batteries.png" alt="Products" class="max-h-[300px] object-contain">
                    </div>
                </div>
            </div>
        </section>

        <!-- Newsletter & Footer (Reused) -->
        <section class="py-16 bg-[#00D1FF] text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row items-center justify-between gap-8">
                    <div>
                        <h2 class="text-3xl font-bold mb-2">Subscribe Newsletter</h2>
                        <p class="text-white/90">Stay up to date with the latest news and offers</p>
                    </div>
                    <form class="w-full max-w-md flex bg-white rounded-md overflow-hidden p-1 shadow-lg">
                        <input type="email" placeholder="Your email address" class="flex-1 px-4 py-2 text-gray-900 placeholder-gray-400 border-none focus:ring-0">
                        <button class="bg-[#FFD700] hover:bg-[#FFC400] text-black px-6 py-2 rounded font-bold transition">Subscribe</button>
                    </form>
                </div>
            </div>
        </section>

        <footer class="bg-[#0A1221] text-white pt-20 pb-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-3 gap-12 mb-20">
                    <div class="space-y-6">
                        <Link href="/" class="flex items-center">
                            <img src="/images/Bronx_Logo_2-removebg-preview.png" alt="Bronx Logo" class="h-10 w-auto">
                        </Link>
                        <p class="text-gray-400 text-sm">Innovation in battery technology</p>
                    </div>
                    <div>
                        <h4 class="font-bold mb-6 uppercase text-sm tracking-wider">Quick Links</h4>
                        <ul class="space-y-4 text-sm text-gray-400">
                            <li><Link href="/" class="hover:text-white transition">Home</Link></li>
                            <li><Link :href="route('product.index')" class="hover:text-white transition">Product</Link></li>
                            <li><Link :href="route('about.index')" class="hover:text-white transition">About Us</Link></li>
                            <li><Link href="#" class="hover:text-white transition">Contact Us</Link></li>
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
                    <p class="text-sm text-gray-500">&copy; 2025 Bronx. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</template>
