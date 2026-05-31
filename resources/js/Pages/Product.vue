<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import Header from '@/Components/Header.vue';
import Sidebar from '@/Components/Sidebar.vue';
import Modal from '@/Components/Modal.vue';
import Toast from '@/Components/Toast.vue';
import Footer from '@/Components/Footer.vue';

const props = defineProps({
    products: Array,
    name: String,
});

const page = usePage();
const cart = computed(() => page.props.cart ?? { item_count: 0 });
const isSidebarOpen = ref(false);
const toast = ref(null);
const searchQuery = ref('');
const isSearchOpen = ref(false);
const isAddingToCart = ref(false);

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

const toggleSearch = () => {
    isSearchOpen.value = !isSearchOpen.value;

    if (!isSearchOpen.value) {
        searchQuery.value = '';
    }
};

// Product Preview Logic
const selectedProduct = ref(null);
const isPreviewOpen = ref(false);
const quantity = ref(1);

const filteredProducts = computed(() => {
    const query = searchQuery.value.trim().toLowerCase();

    if (!query) {
        return props.products;
    }

    return props.products.filter((product) => {
        return (
            product.name.toLowerCase().includes(query) ||
            product.description.toLowerCase().includes(query)
        );
    });
});

const openPreview = (product) => {
    if (product.is_sold_out) {
        toast.value?.add('This product is currently sold out.', 'error');
        return;
    }

    selectedProduct.value = product;
    quantity.value = 1;
    isPreviewOpen.value = true;
};

const closePreview = () => {
    isPreviewOpen.value = false;
    setTimeout(() => {
        selectedProduct.value = null;
    }, 200);
};

const addToCart = () => {
    if (!selectedProduct.value || isAddingToCart.value) {
        return;
    }

    const qty = Math.max(1, Number(quantity.value) || 1);
    quantity.value = qty;
    isAddingToCart.value = true;

    router.post(route('cart.add'), {
        product_id: selectedProduct.value.id,
        quantity: qty,
    }, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            toast.value?.add(`${qty} ${selectedProduct.value.name}(s) added to cart successfully!`);
            closePreview();
        },
        onError: () => {
            toast.value?.add('Unable to add this item to your cart. Please try again.', 'error');
        },
        onFinish: () => {
            isAddingToCart.value = false;
        },
    });
};
</script>

<template>
    <Head title="Products" />

    <div class="min-h-screen overflow-x-hidden bg-white text-gray-900 font-sans flex flex-col pt-28">
        <Toast ref="toast" />
        <Sidebar :open="isSidebarOpen" />
        <Header :cart="cart" @toggle-sidebar="toggleSidebar" @toggle-search="toggleSearch" />

        <!-- Product Header Section -->
        <section class="py-12 bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col gap-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">Products</h1>
                        <nav class="flex text-sm text-gray-500">
                            <Link href="/" class="hover:text-gray-900">Home</Link>
                            <span class="mx-2">/</span>
                            <span class="text-gray-400">Products</span>
                        </nav>
                    </div>

                    <div v-if="isSearchOpen" class="max-w-xl">
                        <input
                            type="search"
                            v-model="searchQuery"
                            placeholder="Search products by name or description"
                            class="w-full rounded-full border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-[#0056D2] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0056D2]/20"
                        />
                    </div>
                </div>
            </div>
        </section>

        <!-- Products Grid Section -->
        <main class="flex-1 py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
                    <template v-if="filteredProducts.length">
                        <div 
                            v-for="product in filteredProducts" 
                            :key="product.id" 
                            @click="openPreview(product)"
                                    class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden cursor-pointer"
                            :class="{ 'opacity-60': product.is_sold_out }"
                        >
                            <!-- Product Image Container -->
                            <div class="aspect-[4/3] bg-gray-50 relative overflow-hidden flex items-center justify-center">
                                <span v-if="product.is_sold_out" class="absolute right-3 top-3 z-10 rounded-full bg-black px-3 py-1 text-xs font-bold text-white">
                                    Sold Out
                                </span>
                                <img 
                                    :src="product.image" 
                                    :alt="product.name" 
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                    @error="(e) => e.target.src = 'https://placehold.co/400x300/0056D2/white?text=' + product.name"
                                >
                            </div>

                            <!-- Product Info -->
                            <div class="p-6">
                                <h3 class="font-bold text-gray-900 mb-2 text-base">{{ product.name }}</h3>
                                <p class="text-gray-500 text-sm mb-4 line-clamp-2 leading-relaxed">{{ product.description }}</p>
                                
                                <div class="flex items-center space-x-2">
                                    <img src="/images/espees_logo.png" alt="Espees" class="w-5 h-5 object-contain">
                                    <span class="font-bold text-gray-900 text-lg">{{ product.price }}</span>
                                </div>
                            </div>
                        </div>
                    </template>

                    <div v-if="!filteredProducts.length" class="col-span-full rounded-2xl border border-dashed border-gray-300 bg-gray-50 p-8 text-center text-gray-500">
                        No products match your search. Try a different keyword.
                    </div>
                </div>
            </div>
        </main>

        <!-- Product Preview Modal -->
        <Modal :show="isPreviewOpen" @close="closePreview" maxWidth="xl">
            <div v-if="selectedProduct" class="bg-white p-5 sm:p-6 rounded-lg relative shadow-2xl">
                <!-- Header -->
                <div class="flex justify-between items-start gap-4 mb-5">
                    <h2 class="min-w-0 text-lg sm:text-xl font-bold text-gray-900 leading-tight">{{ selectedProduct.name }}</h2>
                    <button @click="closePreview" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="grid gap-5 sm:grid-cols-[1fr_0.85fr]">
                    <!-- Left: Image -->
                    <div class="bg-white rounded-lg overflow-hidden flex flex-col">
                        <div class="h-56 sm:h-64 flex items-center justify-center overflow-hidden rounded-t-lg">
                            <img 
                                :src="selectedProduct.image" 
                                :alt="selectedProduct.name" 
                                class="w-full h-full object-cover"
                                @error="(e) => e.target.src = 'https://placehold.co/400x400/0056D2/white?text=' + selectedProduct.name"
                            >
                        </div>
                        <div class="h-20 bg-[#F1F5F9] w-full rounded-b-lg"></div>
                    </div>

                    <!-- Right: Details -->
                    <div class="flex flex-col pt-1">
                        <!-- Price -->
                        <div class="flex items-center space-x-2 mb-4">
                            <img src="/images/espees_logo.png" alt="Espees" class="w-5 h-5 object-contain">
                            <span class="text-2xl sm:text-3xl font-bold text-gray-900 leading-none">{{ selectedProduct.price }}</span>
                        </div>

                        <!-- Description -->
                        <p class="text-gray-500 text-base leading-relaxed mb-6">
                            {{ selectedProduct.description }}
                        </p>

                        <div class="space-y-4">
                            <!-- Quantity -->
                            <div>
                                <label class="text-sm font-semibold text-gray-700 block mb-3">Quantity</label>
                                <div class="flex items-center space-x-2">
                                    <button 
                                        @click="quantity > 1 && quantity--"
                                        class="h-10 w-10 rounded-lg border border-gray-200 bg-white text-xl leading-none text-gray-900 shadow-sm hover:bg-gray-50 transition"
                                    >-</button>
                                    <input 
                                        type="number" 
                                        v-model="quantity" 
                                        class="h-10 w-24 rounded-lg border border-gray-200 text-center text-base text-gray-900 focus:border-[#0056D2] focus:ring-[#0056D2]"
                                        min="1"
                                    >
                                    <button 
                                        @click="quantity++"
                                        class="h-10 w-10 rounded-lg border border-gray-200 bg-white text-xl leading-none text-gray-900 shadow-sm hover:bg-gray-50 transition"
                                    >+</button>
                                </div>
                            </div>

                            <!-- Subtotal -->
                            <p class="text-base text-gray-700">
                                Subtotal: <span class="font-bold text-gray-900">{{ (parseFloat(selectedProduct.price) * quantity).toFixed(0) }}</span>
                            </p>

                             <!-- Actions -->
                             <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 pt-1">
                                 <button 
                                     @click="addToCart"
                                     :disabled="isAddingToCart"
                                     class="inline-flex h-10 w-full sm:w-auto shrink-0 items-center justify-center rounded-lg bg-[#2456C6] px-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#1f4db5]"
                                     :class="{ 'opacity-60 cursor-not-allowed': isAddingToCart }"
                                 >
                                     <svg class="mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                     </svg>
                                     {{ isAddingToCart ? 'Adding...' : 'Add to cart' }}
                                 </button>
                                 <button 
                                     @click="closePreview"
                                     class="h-10 w-full sm:w-auto shrink-0 px-2 text-sm font-semibold text-gray-900 transition hover:text-gray-600"
                                 >
                                     Cancel
                                 </button>
                             </div>
                        </div>
                    </div>
                </div>
            </div>
        </Modal>

        <!-- Newsletter Section (Reused from Home) -->
        <section class="py-16 bg-[#00D1FF] text-white mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row items-center justify-between gap-8 text-center md:text-left">
                    <div class="w-full md:w-auto">
                        <h2 class="text-3xl font-bold mb-2">Subscribe Newsletter</h2>
                        <p class="text-white/90">Stay up to date with the latest news and offers</p>
                    </div>
                    <form class="w-full max-w-md flex flex-col sm:flex-row bg-white rounded-md overflow-hidden p-1 gap-1 sm:gap-0 shadow-lg">
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
