<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { computed, inject, ref } from 'vue';
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';
import Modal from '@/Components/Modal.vue';
import QuantityStepper from '@/Components/QuantityStepper.vue';
import { calculateTotal, formatEspees } from '@/Support/pricing.js';

const props = defineProps({
    products: Array,
    name: String,
});

const toast = inject('toast');
const searchQuery = ref('');
const isSearchOpen = ref(false);
const isAddingToCart = ref(false);
const isBuyingNow = ref(false);

const featuredQuantity = ref(1);
const featuredProduct = computed(() => props.products[0] ?? null);
const isCatalogView = computed(() => props.products.length > 1);

const toggleSearch = () => {
    isSearchOpen.value = !isSearchOpen.value;

    if (!isSearchOpen.value) {
        searchQuery.value = '';
    }
};

const filteredProducts = computed(() => {
    const query = searchQuery.value.trim().toLowerCase();

    if (!query) {
        return props.products;
    }

    return props.products.filter((product) => (
        product.name.toLowerCase().includes(query) ||
        product.description.toLowerCase().includes(query)
    ));
});

// Catalog (multi-product) preview logic — kept for when a second product is added later.
const selectedProduct = ref(null);
const isPreviewOpen = ref(false);
const previewQuantity = ref(1);

const openPreview = (product) => {
    if (product.is_sold_out) {
        toast.value?.add('This product is currently sold out.', 'error');
        return;
    }

    selectedProduct.value = product;
    previewQuantity.value = 1;
    isPreviewOpen.value = true;
};

const closePreview = () => {
    isPreviewOpen.value = false;
    setTimeout(() => {
        selectedProduct.value = null;
    }, 200);
};

const addToCart = (product, quantity, { onSuccess } = {}) => {
    if (!product || isAddingToCart.value) {
        return;
    }

    isAddingToCart.value = true;

    router.post(route('cart.add'), {
        product_id: product.id,
        quantity,
    }, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            toast.value?.add(`${quantity} pack(s) of ${product.name} added to cart.`, 'success');
            onSuccess?.();
        },
        onError: () => {
            toast.value?.add('Unable to add this item to your cart. Please try again.', 'error');
        },
        onFinish: () => {
            isAddingToCart.value = false;
        },
    });
};

const addFeaturedToCart = () => {
    addToCart(featuredProduct.value, featuredQuantity.value);
};

const buyFeaturedNow = () => {
    if (!featuredProduct.value || isBuyingNow.value) {
        return;
    }

    isBuyingNow.value = true;

    router.post(route('cart.add'), {
        product_id: featuredProduct.value.id,
        quantity: featuredQuantity.value,
    }, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => router.visit(route('checkout')),
        onError: () => {
            toast.value?.add('Unable to start checkout. Please try again.', 'error');
        },
        onFinish: () => {
            isBuyingNow.value = false;
        },
    });
};

const addPreviewToCart = () => {
    addToCart(selectedProduct.value, Math.max(1, Number(previewQuantity.value) || 1), {
        onSuccess: closePreview,
    });
};
</script>

<template>
    <Head title="Product" />

    <StorefrontLayout @toggle-search="toggleSearch">
        <!-- Single-product showcase -->
        <template v-if="!isCatalogView && featuredProduct">
            <section class="border-b border-gray-100 bg-white py-12">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <nav class="flex text-sm text-gray-500">
                        <Link href="/" class="hover:text-gray-900">Home</Link>
                        <span class="mx-2">/</span>
                        <span class="text-gray-400">Product</span>
                    </nav>
                </div>
            </section>

            <section class="bg-white py-12 sm:py-20">
                <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-2 lg:items-center lg:gap-16 lg:px-8">
                    <!-- Image -->
                    <div class="relative overflow-hidden rounded-2xl border border-gray-100 bg-gray-50 shadow-sm">
                        <span v-if="featuredProduct.is_sold_out" class="absolute right-4 top-4 z-10 rounded-full bg-black px-3 py-1 text-xs font-bold text-white">
                            Sold Out
                        </span>
                        <img
                            :src="featuredProduct.image"
                            :alt="featuredProduct.name"
                            class="aspect-square w-full object-cover"
                            @error="(e) => e.target.src = 'https://placehold.co/600x600/0056D2/white?text=' + featuredProduct.name"
                        >
                    </div>

                    <!-- Details -->
                    <div class="flex flex-col gap-6">
                        <div>
                            <span class="inline-flex items-center rounded-full bg-brand/10 px-3 py-1 text-xs font-bold uppercase tracking-wide text-brand">
                                Pack of 4 batteries
                            </span>
                            <h1 class="mt-4 text-3xl font-bold text-gray-900 sm:text-4xl">{{ featuredProduct.name }}</h1>
                            <p class="mt-3 leading-relaxed text-gray-600">{{ featuredProduct.description }}</p>
                        </div>

                        <div class="flex items-center gap-2">
                            <img src="/images/espees_logo.png" alt="Espees" class="h-6 w-6 object-contain">
                            <span class="text-3xl font-bold text-gray-900">{{ formatEspees(featuredProduct.price) }}</span>
                            <span class="text-sm text-gray-500">per pack</span>
                        </div>

                        <div v-if="featuredProduct.is_sold_out" class="rounded-lg bg-gray-100 px-4 py-3 text-sm font-semibold text-gray-600">
                            This product is currently sold out. Check back soon.
                        </div>

                        <template v-else>
                            <QuantityStepper v-model="featuredQuantity" id="featured-quantity" />

                            <div class="flex items-center justify-between rounded-xl bg-gray-50 px-5 py-4">
                                <span class="font-semibold text-gray-700">Total</span>
                                <span class="flex items-center gap-2 text-xl font-bold text-gray-900">
                                    <img src="/images/espees_logo.png" alt="Espees" class="h-5 w-5 object-contain">
                                    {{ formatEspees(calculateTotal(featuredProduct.price, featuredQuantity)) }}
                                </span>
                            </div>

                            <div class="flex flex-col gap-3 sm:flex-row">
                                <button
                                    type="button"
                                    @click="buyFeaturedNow"
                                    :disabled="isBuyingNow || isAddingToCart"
                                    class="inline-flex h-12 flex-1 items-center justify-center rounded-lg bg-accent px-6 text-base font-bold text-black shadow-md transition hover:bg-accent-dark disabled:cursor-not-allowed disabled:opacity-60"
                                >
                                    {{ isBuyingNow ? 'Starting checkout...' : 'Buy Now' }}
                                </button>
                                <button
                                    type="button"
                                    @click="addFeaturedToCart"
                                    :disabled="isAddingToCart || isBuyingNow"
                                    class="inline-flex h-12 flex-1 items-center justify-center rounded-lg border-2 border-brand px-6 text-base font-bold text-brand transition hover:bg-brand/5 disabled:cursor-not-allowed disabled:opacity-60"
                                >
                                    {{ isAddingToCart ? 'Adding...' : 'Add to Cart' }}
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </section>
        </template>

        <!-- Catalog grid (used automatically once more than one product is active) -->
        <template v-else>
            <section class="border-b border-gray-100 bg-white py-12">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col gap-6">
                        <div>
                            <h1 class="mb-2 text-3xl font-bold text-gray-900">Products</h1>
                            <nav class="flex text-sm text-gray-500">
                                <Link href="/" class="hover:text-gray-900">Home</Link>
                                <span class="mx-2">/</span>
                                <span class="text-gray-400">Products</span>
                            </nav>
                        </div>

                        <div v-if="isSearchOpen" class="max-w-xl">
                            <label for="product-search" class="sr-only">Search products</label>
                            <input
                                id="product-search"
                                type="search"
                                v-model="searchQuery"
                                placeholder="Search products by name or description"
                                class="w-full rounded-full border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-brand focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand/20"
                            >
                        </div>
                    </div>
                </div>
            </section>

            <section class="flex-1 bg-white py-16">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 lg:gap-8">
                        <template v-if="filteredProducts.length">
                            <div
                                v-for="product in filteredProducts"
                                :key="product.id"
                                @click="openPreview(product)"
                                class="group cursor-pointer overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition-all duration-300 hover:shadow-lg"
                                :class="{ 'opacity-60': product.is_sold_out }"
                            >
                                <div class="relative flex aspect-[4/3] items-center justify-center overflow-hidden bg-gray-50">
                                    <span v-if="product.is_sold_out" class="absolute right-3 top-3 z-10 rounded-full bg-black px-3 py-1 text-xs font-bold text-white">
                                        Sold Out
                                    </span>
                                    <img
                                        :src="product.image"
                                        :alt="product.name"
                                        class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                        @error="(e) => e.target.src = 'https://placehold.co/400x300/0056D2/white?text=' + product.name"
                                    >
                                </div>

                                <div class="p-6">
                                    <h3 class="mb-2 text-base font-bold text-gray-900">{{ product.name }}</h3>
                                    <p class="mb-4 line-clamp-2 text-sm leading-relaxed text-gray-500">{{ product.description }}</p>
                                    <div class="flex items-center space-x-2">
                                        <img src="/images/espees_logo.png" alt="Espees" class="h-5 w-5 object-contain">
                                        <span class="text-lg font-bold text-gray-900">{{ formatEspees(product.price) }}</span>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <div v-else class="col-span-full rounded-2xl border border-dashed border-gray-300 bg-gray-50 p-8 text-center text-gray-500">
                            No products match your search. Try a different keyword.
                        </div>
                    </div>
                </div>
            </section>

            <Modal :show="isPreviewOpen" @close="closePreview" maxWidth="xl">
                <div v-if="selectedProduct" class="relative rounded-lg bg-white p-5 shadow-2xl sm:p-6">
                    <div class="mb-5 flex items-start justify-between gap-4">
                        <h2 class="min-w-0 text-lg font-bold leading-tight text-gray-900 sm:text-xl">{{ selectedProduct.name }}</h2>
                        <button @click="closePreview" class="text-gray-400 transition hover:text-gray-600" aria-label="Close">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="grid gap-5 sm:grid-cols-[1fr_0.85fr]">
                        <div class="flex flex-col overflow-hidden rounded-lg bg-white">
                            <div class="flex h-56 items-center justify-center overflow-hidden rounded-t-lg sm:h-64">
                                <img
                                    :src="selectedProduct.image"
                                    :alt="selectedProduct.name"
                                    class="h-full w-full object-cover"
                                    @error="(e) => e.target.src = 'https://placehold.co/400x400/0056D2/white?text=' + selectedProduct.name"
                                >
                            </div>
                            <div class="h-20 w-full rounded-b-lg bg-gray-100"></div>
                        </div>

                        <div class="flex flex-col pt-1">
                            <div class="mb-4 flex items-center space-x-2">
                                <img src="/images/espees_logo.png" alt="Espees" class="h-5 w-5 object-contain">
                                <span class="text-2xl font-bold leading-none text-gray-900 sm:text-3xl">{{ formatEspees(selectedProduct.price) }}</span>
                            </div>

                            <p class="mb-6 text-base leading-relaxed text-gray-500">{{ selectedProduct.description }}</p>

                            <div class="space-y-4">
                                <QuantityStepper v-model="previewQuantity" id="preview-quantity" />

                                <p class="text-base text-gray-700">
                                    Subtotal:
                                    <span class="font-bold text-gray-900">{{ formatEspees(calculateTotal(selectedProduct.price, previewQuantity)) }}</span>
                                </p>

                                <div class="flex flex-col gap-3 pt-1 sm:flex-row sm:items-center sm:gap-4">
                                    <button
                                        @click="addPreviewToCart"
                                        :disabled="isAddingToCart"
                                        class="inline-flex h-10 w-full shrink-0 items-center justify-center rounded-lg bg-brand px-3 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-dark sm:w-auto"
                                        :class="{ 'cursor-not-allowed opacity-60': isAddingToCart }"
                                    >
                                        {{ isAddingToCart ? 'Adding...' : 'Add to cart' }}
                                    </button>
                                    <button @click="closePreview" class="h-10 w-full shrink-0 px-2 text-sm font-semibold text-gray-900 transition hover:text-gray-600 sm:w-auto">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </Modal>
        </template>
    </StorefrontLayout>
</template>
