<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import Sidebar from '@/Components/Sidebar.vue';
import Header from '@/Components/Header.vue';
import Toast from '@/Components/Toast.vue';

const props = defineProps({
    name: String,
    quote: Object,
    auth: Object,
    sidebarOpen: Boolean,
    cart: Object,
});

const isSidebarOpen = ref(false);
const toast = ref(null);
const page = usePage();

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

const currentSlide = ref(0);
const slides = [
    {
        title: "Power for people who don’t run out",
        description: "Long‑lasting alkaline performance for toys, remotes, gaming controllers and more. Stock up and keep devices ready.",
        buttonText: "View Products",
        image: "/images/battery_ring.png",
        bgColor: "bg-[#0056D2]"
    },
    {
        title: "Reliable energy. Everyday.",
        description: "From AAA to 9V, BronX batteries deliver consistent power you can trust at home and on the go.",
        buttonText: "Shop Now",
        image: "/images/battery_ring.png",
        bgColor: "bg-[#0047AB]"
    }
];

const nextSlide = () => {
    currentSlide.value = (currentSlide.value + 1) % slides.length;
};

const prevSlide = () => {
    currentSlide.value = (currentSlide.value - 1 + slides.length) % slides.length;
};

let autoplayInterval;

onMounted(() => {
    autoplayInterval = setInterval(nextSlide, 5000);

    if (page.props.flash?.success) {
        toast.value?.add(page.props.flash.success, 'success');
    }

    if (page.props.flash?.error) {
        toast.value?.add(page.props.flash.error, 'error');
    }
});

onUnmounted(() => {
    if (autoplayInterval) clearInterval(autoplayInterval);
});

const batterySpecs = [
    { label: 'BATTERY CAPACITY', value: '420 mAh (typical)' },
    { label: 'CHEMISTRY', value: 'Lithium-ion polymer (Li-Po)' },
    { label: 'NOMINAL VOLTAGE', value: '3.85 V' },
    { label: 'ENERGY', value: '1.62 Wh' },
    { label: 'CHARGE TIME (0–100%)', value: '~90 minutes' },
    { label: 'FAST CHARGE', value: '0–50% in ~30 minutes' },
    { label: 'CYCLE LIFE', value: '> 500 cycles to 80% capacity' },
    { label: 'OPERATING TEMP', value: '0°C to 35°C (32°F to 95°F)' },
];

const currentTestimonial = ref(0);
const testimonials = [
    {
        text: '"The Bronxx Core Battery powers my delivery scooter for an entire shift. Zero voltage sag and charges fast."',
        name: "Ava Thompson",
        role: "Courier, Northwind Logistics",
        initials: "AT"
    },
    {
        text: '"Switching to Bronx batteries for our wireless mics was a game-changer. They last twice as long as our previous brand."',
        name: "James Wilson",
        role: "Event Coordinator, Echo Productions",
        initials: "JW"
    },
    {
        text: '"I use these for my gaming controllers and they are incredible. I don\'t have to swap them out nearly as often."',
        name: "Marcus Chen",
        role: "Pro Gamer, Zenith eSports",
        initials: "MC"
    }
];

const nextTestimonial = () => {
    currentTestimonial.value = (currentTestimonial.value + 1) % testimonials.length;
};

const prevTestimonial = () => {
    currentTestimonial.value = (currentTestimonial.value - 1 + testimonials.length) % testimonials.length;
};

const features = [
    {
        title: 'Long-lasting Capacity',
        description: 'High-density Li-Po cells deliver all-day power with optimized discharge for consistent performance.',
        icon: 'M20 7H4a2 2 0 00-2 2v6a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zM4 9h16v6H4V9z'
    },
    {
        title: 'Fast Charging',
        description: '0–50% in about 30 minutes with smart thermal management and charge protection.',
        icon: 'M13 10V3L4 14h7v7l9-11h-7z'
    },
    {
        title: 'Safe & Efficient',
        description: 'Multi-layer safety (OVP/OCP/SCP) and temperature monitoring for reliable longevity.',
        icon: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'
    }
];
</script>

<template>
    <Head :title="name" />

    <div class="min-h-screen bg-white text-gray-900 font-sans pt-28">
        <Toast ref="toast" />
        <Sidebar :open="isSidebarOpen" />
        <Header :cart="cart" @toggle-sidebar="toggleSidebar" />

        <!-- 1. Hero Section -->
        <section class="relative overflow-hidden min-h-[600px] flex items-center transition-colors duration-700" :class="slides[currentSlide].bgColor">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full relative">
                <div class="relative" role="region" aria-roledescription="carousel" data-slot="carousel">
                    <div class="overflow-hidden" data-slot="carousel-content">
                        <div 
                            class="flex -ml-4 transition-transform duration-700 ease-in-out" 
                            :style="{ transform: `translate3d(-${currentSlide * 100}%, 0px, 0px)` }"
                        >
                            <div 
                                v-for="(slide, index) in slides" 
                                :key="index"
                                role="group" 
                                aria-roledescription="slide" 
                                data-slot="carousel-item" 
                                class="min-w-0 shrink-0 grow-0 basis-full pl-4"
                            >
                                <div class="flex flex-col sm:flex-row items-center justify-between gap-8 py-20">
                                    <div class="flex-1 z-10 gap-3 flex flex-col text-white">
                                        <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-6xl font-bold leading-tight">
                                            {{ slide.title }}
                                        </h1>
                                        <p class="text-sm sm:text-sm md:text-base lg:text-lg text-white/80 max-w-xl">
                                            {{ slide.description }}
                                        </p>
                                        <Link :href="route('product.index')">
                                            <button 
                                                data-slot="button" 
                                                class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md transition-all disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg:not([class*='size-'])]:size-4 shrink-0 [&_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive h-9 has-[>svg]:px-3 bg-yellow-400 hover:bg-yellow-500 text-black font-bold text-xs sm:text-sm px-8 py-6"
                                            >
                                                {{ slide.buttonText }} →
                                            </button>
                                        </Link>
                                    </div>
                                    <div class="lg:flex flex-1 justify-center items-center relative h-96">
                                        <img 
                                            :src="slide.image" 
                                            alt="Battery Pack Illustration" 
                                            class="max-h-full object-contain drop-shadow-2xl"
                                            @error="(e) => e.target.src = 'https://placehold.co/400x600/0056D2/white?text=Batteries'"
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Slider Arrows -->
                    <button 
                        @click="prevSlide"
                        data-slot="carousel-previous" 
                        class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg:not([class*='size-'])]:size-4 shrink-0 [&_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive shadow-xs hover:text-accent-foreground dark:bg-input/30 dark:border-input dark:hover:bg-input/50 absolute size-10 rounded-full top-1/2 -left-12 -translate-y-1/2 bg-white/20 hover:bg-white/30 border-0 text-white z-20"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left">
                            <path d="m12 19-7-7 7-7"></path>
                            <path d="M19 12H5"></path>
                        </svg>
                        <span class="sr-only">Previous slide</span>
                    </button>
                    <button 
                        @click="nextSlide"
                        data-slot="carousel-next" 
                        class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg:not([class*='size-'])]:size-4 shrink-0 [&_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive shadow-xs hover:text-accent-foreground dark:bg-input/30 dark:border-input dark:hover:bg-input/50 absolute size-10 rounded-full top-1/2 -right-12 -translate-y-1/2 bg-white/20 hover:bg-white/30 border-0 text-white z-20"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right">
                            <path d="M5 12h14"></path>
                            <path d="m12 5 7 7-7 7"></path>
                        </svg>
                        <span class="sr-only">Next slide</span>
                    </button>
                </div>

                <!-- Slide Indicators -->
                <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex space-x-3 z-20">
                    <button 
                        v-for="(_, index) in slides" 
                        :key="index"
                        @click="currentSlide = index"
                        class="w-3 h-3 rounded-full transition-all duration-300"
                        :class="currentSlide === index ? 'bg-white w-8' : 'bg-white/40 hover:bg-white/60'"
                    ></button>
                </div>
            </div>
        </section>

        <!-- 2. Battery Features Section -->
        <section class="py-24 bg-white overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-3 gap-12 items-center">
                    <!-- Left: Heading -->
                    <div class="space-y-4">
                        <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Battery Features</p>
                        <h2 class="text-4xl md:text-5xl font-bold text-[#0F172A] leading-tight">
                            POWER THAT LASTS SMART BATTERY TECH.
                        </h2>
                    </div>

                    <!-- Center: Image -->
                    <div class="relative flex justify-center order-first lg:order-none mb-12 lg:mb-0">
                        <div class="relative w-[350px] h-[350px] md:w-[450px] md:h-[450px] flex items-center justify-center p-8">
                            <img src="/images/battery_ring.png" alt="Battery" class="w-full h-full object-contain" @error="(e) => e.target.src = 'https://placehold.co/300x300/white/0056D2?text=Battery'">
                        </div>
                    </div>

                    <!-- Right: Features -->
                    <div class="space-y-10">
                        <div v-for="feature in features" :key="feature.title" class="flex gap-5">
                            <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center text-[#0F172A]">
                                <svg v-if="feature.title === 'Long-lasting Capacity'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 7H3a2 2 0 00-2 2v6a2 2 0 002 2h18a2 2 0 002-2V9a2 2 0 00-2-2zM4 9h12v6H4V9z" />
                                </svg>
                                <svg v-else-if="feature.title === 'Fast Charging'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                <svg v-else-if="feature.title === 'Safe & Efficient'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-[#0F172A] mb-2">{{ feature.title }}</h3>
                                <p class="text-sm text-gray-500 leading-relaxed">{{ feature.description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 3. How It's Made Section -->
        <section class="py-32 bg-[#4A4A4A] text-center text-white relative">
            <div class="max-w-4xl mx-auto px-4">
                <p class="text-xs font-bold text-[#FFC107] uppercase tracking-[0.3em] mb-4">How it's made</p>
                <h2 class="text-6xl font-bold mb-12 uppercase tracking-wide">How to made</h2>
                <div class="flex justify-center">
                    <button class="w-20 h-20 rounded-full bg-[#FFC107] flex items-center justify-center text-black hover:scale-110 transition duration-300">
                        <svg class="w-8 h-8 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z" /></svg>
                    </button>
                </div>
            </div>
        </section>

        <!-- 4. Battery Specifications Section -->
        <section class="py-24 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">Power that lasts</p>
                <h2 class="text-5xl font-bold text-gray-900 mb-16">BATTERY SPECIFICATIONS</h2>
                
                <div class="grid lg:grid-cols-2 gap-16 items-start">
                    <div class="relative">
                        <img src="/images/bronx_spec.png" alt="Battery Specs" class="w-full object-contain" @error="(e) => e.target.src = 'https://placehold.co/600x400/white/0056D2?text=Batteries+with+Lightning'">
                    </div>
                    <div class="grid sm:grid-cols-2 gap-x-8 gap-y-12">
                        <div v-for="spec in batterySpecs" :key="spec.label" class="border-b border-gray-100 pb-6">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">{{ spec.label }}</p>
                            <p class="text-lg font-bold text-gray-900">{{ spec.value }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 5. Testimonials Section -->
        <section class="py-32 bg-[#F8F9FA]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <p class="text-sm font-bold text-[#0056D2] uppercase tracking-widest mb-4">Testimonials</p>
                <h2 class="text-5xl font-bold text-gray-900 mb-16">What our customers say</h2>
                
                <div class="relative max-w-4xl mx-auto" role="region" aria-roledescription="carousel" data-slot="carousel">
                    <!-- Testimonial Content with Horizontal Slide -->
                    <div class="overflow-hidden" data-slot="carousel-content">
                        <div 
                            class="flex -ml-4 transition-transform duration-700 ease-in-out" 
                            :style="{ transform: `translate3d(-${currentTestimonial * 100}%, 0px, 0px)` }"
                        >
                            <div 
                                v-for="(testimonial, index) in testimonials" 
                                :key="index"
                                role="group" 
                                aria-roledescription="slide" 
                                data-slot="carousel-item" 
                                class="min-w-0 shrink-0 grow-0 basis-full pl-4"
                            >
                                <div class="bg-white rounded-2xl p-12 shadow-sm border border-gray-100 flex flex-col justify-center min-h-[300px]">
                                    <p class="text-2xl text-gray-700 leading-relaxed mb-8 italic">
                                        {{ testimonial.text }}
                                    </p>
                                    <div class="flex items-center justify-center">
                                        <div class="w-12 h-12 rounded-full bg-[#D1D9E6] flex items-center justify-center text-[#0056D2] font-bold mr-4">
                                            {{ testimonial.initials }}
                                        </div>
                                        <div class="text-left">
                                            <p class="font-bold text-gray-900">{{ testimonial.name }}</p>
                                            <p class="text-sm text-gray-500">{{ testimonial.role }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Slider Nav (Same icons as Hero) -->
                    <button 
                        @click="prevTestimonial"
                        data-slot="carousel-previous"
                        class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg:not([class*='size-'])]:size-4 shrink-0 [&_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive shadow-xs hover:text-accent-foreground dark:bg-input/30 dark:border-input dark:hover:bg-input/50 absolute size-10 rounded-full top-1/2 -left-12 -translate-y-1/2 bg-white hover:bg-gray-50 border border-gray-100 text-gray-400 hover:text-gray-900 z-10"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left">
                            <path d="m12 19-7-7 7-7"></path>
                            <path d="M19 12H5"></path>
                        </svg>
                        <span class="sr-only">Previous slide</span>
                    </button>
                    <button 
                        @click="nextTestimonial"
                        data-slot="carousel-next"
                        class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg:not([class*='size-'])]:size-4 shrink-0 [&_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive shadow-xs hover:text-accent-foreground dark:bg-input/30 dark:border-input dark:hover:bg-input/50 absolute size-10 rounded-full top-1/2 -right-12 -translate-y-1/2 bg-white hover:bg-gray-50 border border-gray-100 text-gray-400 hover:text-gray-900 z-10"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right">
                            <path d="M5 12h14"></path>
                            <path d="m12 5 7 7-7 7"></path>
                        </svg>
                        <span class="sr-only">Next slide</span>
                    </button>
                    
                    <!-- Dots -->
                    <div class="mt-8 flex justify-center space-x-2">
                        <button 
                            v-for="(_, index) in testimonials" 
                            :key="index"
                            @click="currentTestimonial = index"
                            class="h-1.5 rounded-full transition-all duration-300"
                            :class="currentTestimonial === index ? 'w-10 bg-gray-900' : 'w-2.5 bg-gray-300 hover:bg-gray-400'"
                        ></button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Newsletter Section -->
        <section class="py-16 bg-[#00D1FF] text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row items-center justify-between gap-8">
                    <div>
                        <h2 class="text-3xl font-bold mb-2">Subscribe Newsletter</h2>
                        <p class="text-white/90">Stay up to date with the latest news and offers</p>
                    </div>
                    <form class="w-full max-w-md flex bg-white rounded-md overflow-hidden p-1">
                        <input 
                            type="email" 
                            placeholder="Your email address" 
                            class="flex-1 px-4 py-2 text-gray-900 placeholder-gray-400 border-none focus:ring-0"
                        >
                        <button class="bg-[#FFD700] hover:bg-[#FFC400] text-black px-6 py-2 rounded font-bold transition">
                            Subscribe
                        </button>
                    </form>
                </div>
            </div>
        </section>

        <!-- Footer Section -->
        <footer class="bg-[#0A1221] text-white pt-20 pb-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-3 gap-12 mb-20">
                    <!-- Logo and Tagline -->
                    <div class="space-y-6">
                        <Link href="/" class="flex items-center">
                            <img src="/images/Bronx_Logo_2-removebg-preview.png" alt="Bronx Logo" class="h-10 w-auto">
                        </Link>
                        <p class="text-gray-400 text-sm">
                            Innovation in battery technology
                        </p>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h4 class="font-bold mb-6 uppercase text-sm tracking-wider">Quick Links</h4>
                        <ul class="space-y-4 text-sm text-gray-400">
                            <li><Link href="#" class="hover:text-white transition">Home</Link></li>
                            <li><Link href="#" class="hover:text-white transition">Product</Link></li>
                            <li><Link href="#" class="hover:text-white transition">About Us</Link></li>
                            <li><Link href="#" class="hover:text-white transition">Contact Us</Link></li>
                        </ul>
                    </div>

                    <!-- Opening Time -->
                    <div>
                        <h4 class="font-bold mb-6 uppercase text-sm tracking-wider">Opening Time</h4>
                        <ul class="space-y-4 text-sm text-gray-400">
                            <li>Monday - Friday: 9am - 5pm</li>
                            <li>Saturday: 10am - 3pm</li>
                            <li>Sunday: Closed</li>
                        </ul>
                    </div>
                </div>

                <!-- Bottom Footer -->
                <div class="border-t border-gray-800 pt-8 flex flex-col md:row items-center justify-between gap-4">
                    <p class="text-sm text-gray-500">
                        &copy; 2025 Bronx. All rights reserved.
                    </p>
                    <div class="flex space-x-6">
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v1.385z"/></svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.238 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

body {
    font-family: 'Inter', sans-serif;
}
</style>
