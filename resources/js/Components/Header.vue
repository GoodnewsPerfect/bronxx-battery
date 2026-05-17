<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    cart: Object,
});

const page = usePage();
const user = computed(() => page.props.auth.user);
const isCartOpen = ref(false);
const activeCart = computed(() => props.cart ?? page.props.cart ?? { items: {}, item_count: 0, total: '0.00' });
const cartItems = computed(() => Object.values(activeCart.value.items ?? {}));
const cartTotal = computed(() => activeCart.value.total ?? '0.00');

const emit = defineEmits(['toggleSidebar', 'toggleSearch']);

const updateQuantity = (item, quantity) => {
    const nextQuantity = Math.max(1, quantity);

    router.patch(route('cart.update', item.id), {
        quantity: nextQuantity,
    }, {
        preserveScroll: true,
        preserveState: true,
    });
};

const removeItem = (item) => {
    router.delete(route('cart.destroy', item.id), {
        preserveScroll: true,
        preserveState: true,
    });
};

const clearCart = () => {
    router.delete(route('cart.clear'), {
        preserveScroll: true,
        preserveState: true,
    });
};
</script>

<template>
    <header class="fixed top-0 z-50 w-full transition-all duration-300 shadow-lg">
        <!-- Top Bar -->
        <div class="bg-[#0047AB] py-2 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto flex justify-start space-x-4 text-xs text-white/90">
                <template v-if="!user">
                    <Link :href="route('login')" class="hover:text-white transition">Log in</Link>
                    <span class="text-white/40">|</span>
                    <Link :href="route('register')" class="hover:text-white transition">Register</Link>
                </template>
                <template v-else>
                    <span class="text-white/90">Welcome, {{ user.name }}</span>
                    <span class="text-white/40">|</span>
                    <Link :href="route('logout')" method="post" as="button" class="hover:text-white transition">Logout</Link>
                </template>
            </div>
        </div>

        <!-- Main Navigation -->
        <div class="bg-[#0056D2] py-4 px-4 sm:px-6 lg:px-8 shadow-sm">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center">
                    <Link href="/" class="flex items-center">
                        <img src="/images/Bronx_Logo_2-removebg-preview.png" alt="Bronx Logo" class="h-10 w-auto">
                    </Link>
                </div>

                <!-- Desktop Nav -->
                <nav class="hidden md:flex items-center space-x-8">
                    <Link href="/" class="text-white hover:text-white/80 font-medium transition">Home</Link>
                    <Link :href="route('product.index')" class="text-white hover:text-white/80 font-medium transition">Product</Link>
                    <Link :href="route('about.index')" class="text-white hover:text-white/80 font-medium transition">About</Link>
                    <Link :href="route('contact.index')" class="text-white hover:text-white/80 font-medium transition">Contact</Link>
                    <Link :href="user ? route('orders.index') : route('login')" class="text-white hover:text-white/80 font-medium transition">My Account</Link>
                </nav>

                <!-- Icons -->
                <div class="flex items-center space-x-5 text-white">
                    <button @click="emit('toggleSearch')" class="hover:text-white/80 transition" aria-label="Search products">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                    <button @click="isCartOpen = true" class="relative hover:text-white/80 transition" aria-label="Open cart">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span v-if="activeCart.item_count > 0" class="absolute -top-2 -right-2 flex items-center justify-center w-4 h-4 text-[10px] text-white bg-[#FF4D4D] rounded-full">
                            {{ activeCart.item_count }}
                        </span>
                    </button>
                    <!-- Mobile Menu Button -->
                    <button @click="emit('toggleSidebar')" class="md:hidden hover:text-white/80 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <Transition
        enter-active-class="transition-opacity duration-200"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition-opacity duration-150"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div
            v-if="isCartOpen"
            class="fixed inset-0 z-[60] bg-black/45"
            @click="isCartOpen = false"
        />
    </Transition>

    <Transition
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="translate-x-full"
        enter-to-class="translate-x-0"
        leave-active-class="transition duration-200 ease-in"
        leave-from-class="translate-x-0"
        leave-to-class="translate-x-full"
    >
        <aside
            v-if="isCartOpen"
            class="fixed right-0 top-0 z-[70] flex h-screen w-full max-w-[600px] flex-col bg-white shadow-2xl"
        >
            <div class="px-8 pt-8 text-center">
                <h2 class="text-xl font-bold text-gray-900">Cart</h2>
                <button
                    @click="isCartOpen = false"
                    class="mx-auto mt-7 flex h-8 w-8 items-center justify-center text-gray-900 hover:text-gray-600"
                    aria-label="Close cart"
                >
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto px-6 pt-6">
                <div v-if="!cartItems.length" class="py-16 text-center text-gray-500">
                    Your cart is empty.
                </div>

                <div
                    v-for="item in cartItems"
                    :key="item.id"
                    class="flex gap-4 border-b border-gray-200 py-5"
                >
                    <img
                        :src="item.image || '/images/product1.jpg'"
                        :alt="item.name"
                        class="h-24 w-24 shrink-0 rounded-md object-cover"
                    >
                    <div class="min-w-0 flex-1">
                        <h3 class="truncate text-lg font-bold text-gray-900">{{ item.name }}</h3>
                        <div class="mt-2 flex items-center gap-2 text-base text-gray-600">
                            <img src="/images/espees_logo.png" alt="Espees" class="h-5 w-5 object-contain">
                            <span>{{ item.price }}</span>
                        </div>
                        <div class="mt-3 flex items-center gap-5">
                            <button
                                @click="updateQuantity(item, item.quantity - 1)"
                                class="flex h-9 w-9 items-center justify-center rounded-md border border-gray-200 text-2xl leading-none text-gray-900 shadow-sm hover:bg-gray-50"
                                aria-label="Decrease quantity"
                            >
                                -
                            </button>
                            <span class="min-w-5 text-center text-xl font-medium text-gray-900">{{ item.quantity }}</span>
                            <button
                                @click="updateQuantity(item, item.quantity + 1)"
                                class="flex h-9 w-9 items-center justify-center rounded-md border border-gray-200 text-2xl leading-none text-gray-900 shadow-sm hover:bg-gray-50"
                                aria-label="Increase quantity"
                            >
                                +
                            </button>
                        </div>
                    </div>
                    <button
                        @click="removeItem(item)"
                        class="mt-16 flex h-8 w-8 shrink-0 items-center justify-center text-[#FF4D4D] hover:text-red-600"
                        aria-label="Remove item"
                    >
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0h8m-5-3h2a1 1 0 011 1v2h-4V5a1 1 0 011-1z" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex gap-3 px-6 pb-6 pt-4">
                <div v-if="cartItems.length" class="mr-auto flex items-center text-sm font-semibold text-gray-700">
                    Total: {{ cartTotal }}
                </div>
                <Link
                    :href="route('checkout')"
                    class="flex-1 rounded-lg bg-[#F9AD32] px-5 py-3 text-center font-semibold text-black transition hover:bg-[#f3a11d]"
                    :class="{ 'pointer-events-none opacity-50': !cartItems.length }"
                >
                    Checkout
                </Link>
                <button
                    @click="clearCart"
                    class="rounded-lg border border-gray-200 bg-white px-6 py-3 font-semibold text-gray-900 shadow-sm transition hover:bg-gray-50"
                    :disabled="!cartItems.length"
                    :class="{ 'opacity-50': !cartItems.length }"
                >
                    Clear
                </button>
            </div>
        </aside>
    </Transition>
</template>
