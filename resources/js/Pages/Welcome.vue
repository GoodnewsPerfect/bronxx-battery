<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import Sidebar from '@/Components/Sidebar.vue';
import Header from '@/Components/Header.vue';
import Toast from '@/Components/Toast.vue';
import Footer from '@/Components/Footer.vue';

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

    <div class="min-h-screen overflow-x-hidden bg-white text-gray-900 font-sans pt-28">
        <Toast ref="toast" />
        <Sidebar :open="isSidebarOpen" />
        <Header :cart="cart" @toggle-sidebar="toggleSidebar" />

        <!-- 1. Hero Section -->
        <section class="relative overflow-hidden min-h-[520px] sm:min-h-[600px] flex items-center transition-colors duration-700" :class="slides[currentSlide].bgColor">
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
                                <div class="flex flex-col sm:flex-row items-center justify-between gap-8 py-12 sm:py-20">
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
                                                class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md transition-all disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg:not([class*='size-'])]:size-4 shrink-0 [&_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive h-9 has-[>svg]:px-3 bg-yellow-400 hover:bg-yellow-500 text-black font-bold text-xs sm:text-sm px-6 sm:px-8 py-5 sm:py-6"
                                            >
                                                {{ slide.buttonText }} →
                                            </button>
                                        </Link>
                                    </div>
                                    <div class="flex flex-1 justify-center items-center relative h-64 sm:h-80 lg:h-96 w-full">
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
                        class="hidden sm:inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg:not([class*='size-'])]:size-4 shrink-0 [&_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive shadow-xs hover:text-accent-foreground dark:bg-input/30 dark:border-input dark:hover:bg-input/50 absolute size-10 rounded-full top-1/2 left-2 lg:-left-12 -translate-y-1/2 bg-white/20 hover:bg-white/30 border-0 text-white z-20"
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
                        class="hidden sm:inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg:not([class*='size-'])]:size-4 shrink-0 [&_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive shadow-xs hover:text-accent-foreground dark:bg-input/30 dark:border-input dark:hover:bg-input/50 absolute size-10 rounded-full top-1/2 right-2 lg:-right-12 -translate-y-1/2 bg-white/20 hover:bg-white/30 border-0 text-white z-20"
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
        <section class="py-16 sm:py-24 bg-white overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-3 gap-12 items-center">
                    <!-- Left: Heading -->
                    <div class="space-y-4">
                        <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Battery Features</p>
                        <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-[#0F172A] leading-tight">
                            POWER THAT LASTS SMART BATTERY TECH.
                        </h2>
                    </div>

                    <!-- Center: Image -->
                    <div class="relative flex justify-center order-first lg:order-none mb-12 lg:mb-0">
                        <div class="relative w-full max-w-[350px] h-[300px] md:max-w-[450px] md:h-[450px] flex items-center justify-center p-4 sm:p-8">
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
        <section class="py-20 sm:py-32 bg-[#4A4A4A] text-center text-white relative">
            <div class="max-w-4xl mx-auto px-4">
                <p class="text-xs font-bold text-[#FFC107] uppercase tracking-[0.3em] mb-4">How it's made</p>
                <h2 class="text-3xl sm:text-5xl lg:text-6xl font-bold mb-10 sm:mb-12 uppercase tracking-wide">How it is made</h2>
                <div class="flex justify-center">
                    <button class="w-20 h-20 rounded-full bg-[#FFC107] flex items-center justify-center text-black hover:scale-110 transition duration-300">
                        <svg class="w-8 h-8 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z" /></svg>
                    </button>
                </div>
            </div>
        </section>

        <!-- 4. Battery Specifications Section -->
        <section class="py-16 sm:py-24 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">Power that lasts</p>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-10 sm:mb-16">BATTERY SPECIFICATIONS</h2>
                
                <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-start">
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
        <section class="py-20 sm:py-32 bg-[#F8F9FA]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <p class="text-sm font-bold text-[#0056D2] uppercase tracking-widest mb-4">Testimonials</p>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-10 sm:mb-16">What our customers say</h2>
                
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
                                <div class="bg-white rounded-2xl p-6 sm:p-12 shadow-sm border border-gray-100 flex flex-col justify-center min-h-[260px] sm:min-h-[300px]">
                                    <p class="text-lg sm:text-2xl text-gray-700 leading-relaxed mb-8 italic">
                                        {{ testimonial.text }}
                                    </p>
                                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-0">
                                        <div class="w-12 h-12 rounded-full bg-[#D1D9E6] flex items-center justify-center text-[#0056D2] font-bold sm:mr-4">
                                            {{ testimonial.initials }}
                                        </div>
                                        <div class="text-center sm:text-left">
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
                        class="hidden sm:inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg:not([class*='size-'])]:size-4 shrink-0 [&_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive shadow-xs hover:text-accent-foreground dark:bg-input/30 dark:border-input dark:hover:bg-input/50 absolute size-10 rounded-full top-1/2 left-2 lg:-left-12 -translate-y-1/2 bg-white hover:bg-gray-50 border border-gray-100 text-gray-400 hover:text-gray-900 z-10"
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
                        class="hidden sm:inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg:not([class*='size-'])]:size-4 shrink-0 [&_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive shadow-xs hover:text-accent-foreground dark:bg-input/30 dark:border-input dark:hover:bg-input/50 absolute size-10 rounded-full top-1/2 right-2 lg:-right-12 -translate-y-1/2 bg-white hover:bg-gray-50 border border-gray-100 text-gray-400 hover:text-gray-900 z-10"
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
                <div class="flex flex-col md:flex-row items-center justify-between gap-8 text-center md:text-left">
                    <div class="w-full md:w-auto">
                        <h2 class="text-3xl font-bold mb-2">Subscribe Newsletter</h2>
                        <p class="text-white/90">Stay up to date with the latest news and offers</p>
                    </div>
                    <form class="w-full max-w-md flex flex-col sm:flex-row bg-white rounded-md overflow-hidden p-1 gap-1 sm:gap-0">
                        <input 
                            type="email" 
                            placeholder="Your email address" 
                            class="min-w-0 flex-1 px-4 py-3 sm:py-2 text-gray-900 placeholder-gray-400 border-none focus:ring-0"
                        >
                        <button class="bg-[#FFD700] hover:bg-[#FFC400] text-black px-6 py-3 sm:py-2 rounded font-bold transition">
                            Subscribe
                        </button>
                    </form>
                </div>
            </div>
        </section>

        <Footer />
    </div>
</template>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

body {
    font-family: 'Inter', sans-serif;
}
</style>
