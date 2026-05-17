<script setup>
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import Header from '@/Components/Header.vue';
import Sidebar from '@/Components/Sidebar.vue';

const props = defineProps({
    cartItems: Array,
    cartTotal: Number,
});

const page = usePage();
const cart = computed(() => page.props.cart ?? {
    items: {},
    item_count: 0,
    total: Number(props.cartTotal ?? 0).toFixed(2),
});
const summaryItems = computed(() => Object.values(cart.value.items ?? {}));
const subtotal = computed(() => Number(cart.value.total ?? props.cartTotal ?? 0));
const shipping = 0;
const total = computed(() => subtotal.value + shipping);
const isSidebarOpen = ref(false);
const isProcessing = ref(false);
const paymentError = ref('');
const returnedPaymentMessage = new URLSearchParams(window.location.search).get('message')
    || new URLSearchParams(window.location.search).get('status_details')
    || new URLSearchParams(window.location.search).get('error')
    || '';

const form = useForm({
    shipping_address: '',
    payment_method: 'espees',
});

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

const updateQuantity = (item, quantity) => {
    router.patch(route('cart.update', item.id), {
        quantity: Math.max(1, quantity),
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

const completeOrder = () => {
    if (isProcessing.value) {
        return;
    }

    paymentError.value = '';
    form.clearErrors();
    isProcessing.value = true;

    const jsonHeaders = {
        Accept: 'application/json',
    };

    window.axios.post(route('checkout.store'), form.data(), {
        headers: jsonHeaders,
    })
        .then(({ data }) => {
            const orderId = String(data.order_id);
            const successUrl = new URL(route('order.confirmation', orderId, false), window.location.origin);
            const failUrl = new URL(route('order.confirmation', orderId, false), window.location.origin);

            successUrl.searchParams.set('espees_return', 'success');
            failUrl.searchParams.set('espees_return', 'failed');

            return window.axios.post(route('payment.initialize-espees'), {
                orderId,
                amount: data.amount,
                description: data.description,
                successUrl: successUrl.href,
                failUrl: failUrl.href,
            }, {
                headers: jsonHeaders,
            });
        })
        .then(({ data }) => {
            if (data.status === 'success' && data.payment_url) {
                window.location.href = data.payment_url;
                return;
            }

            paymentError.value = data.message || 'Unable to start Espees payment. Please try again.';
        })
        .catch((error) => {
            if (error.response?.status === 422 && error.response?.data?.errors) {
                form.setError(error.response.data.errors);
                paymentError.value = Object.values(error.response.data.errors).flat()[0]
                    || 'Please check the highlighted fields and try again.';
                return;
            }

            paymentError.value = error.response?.data?.message
                || error.response?.data?.status_details
                || 'Unable to start Espees payment. Please try again.';
        })
        .finally(() => {
            isProcessing.value = false;
        });
};
</script>

<template>
    <Head title="Checkout" />

    <div class="min-h-screen bg-white text-gray-900 font-sans flex flex-col pt-28">
        <Sidebar :open="isSidebarOpen" />
        <Header :cart="cart" @toggle-sidebar="toggleSidebar" />

        <main class="flex-1">
            <section class="border-b border-gray-200 bg-white pb-16 pt-0">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <h1 class="text-3xl font-bold text-gray-900">Checkout</h1>
                    <nav class="mt-2 flex text-lg text-gray-500">
                        <Link href="/" class="hover:text-gray-900">Home</Link>
                        <span class="mx-2">/</span>
                        <span>Checkout</span>
                    </nav>
                </div>
            </section>

            <section class="py-20">
                <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[1fr_400px] lg:px-8 xl:grid-cols-[1fr_420px]">
                    <form id="checkout-form" class="space-y-8" @submit.prevent="completeOrder">
                        <div class="rounded-xl border border-gray-200 bg-white p-8">
                            <h2 class="mb-8 text-2xl font-bold text-gray-900">Delivery Address</h2>

                            <label class="mb-3 block text-lg font-medium text-gray-600" for="shipping_address">
                                Delivery Address <span class="text-sm font-normal text-gray-400">Optional</span>
                            </label>
                            <input
                                id="shipping_address"
                                v-model="form.shipping_address"
                                type="text"
                                placeholder="123 Main St, City, State ZIP"
                                class="h-14 w-full rounded-lg border border-gray-200 px-4 text-lg shadow-sm focus:border-[#2456C6] focus:ring-[#2456C6]"
                            >
                            <p v-if="form.errors.shipping_address" class="mt-2 text-sm text-red-600">
                                {{ form.errors.shipping_address }}
                            </p>
                        </div>

                        <div class="rounded-xl border border-gray-200 bg-white p-8">
                            <h2 class="mb-8 text-2xl font-bold text-gray-900">Payment Method</h2>

                            <label class="flex h-16 items-center gap-3 rounded border border-gray-200 px-4 text-lg font-bold text-gray-900">
                                <input
                                    v-model="form.payment_method"
                                    type="radio"
                                    value="espees"
                                    class="h-5 w-5 border-gray-300 text-[#2456C6] focus:ring-[#2456C6]"
                                >
                                <img src="/images/espees_logo.png" alt="Espees" class="h-6 w-6 object-contain">
                                Espees
                            </label>
                            <p v-if="form.errors.payment_method" class="mt-2 text-sm text-red-600">
                                {{ form.errors.payment_method }}
                            </p>
                            <p v-if="returnedPaymentMessage" class="mt-4 text-sm text-red-600">
                                {{ returnedPaymentMessage }}
                            </p>
                            <p v-if="paymentError" class="mt-4 text-sm text-red-600">
                                {{ paymentError }}
                            </p>
                        </div>
                    </form>

                    <aside class="rounded-xl border border-gray-200 bg-white p-8">
                        <h2 class="mb-7 text-2xl font-bold text-gray-900">Order Summary</h2>

                        <div v-if="!summaryItems.length" class="py-12 text-center text-gray-500">
                            Your cart is empty.
                        </div>

                        <div
                            v-for="item in summaryItems"
                            :key="item.id"
                            class="flex gap-4 border-b border-gray-200 py-5 first:pt-0"
                        >
                            <img
                                :src="item.image || '/images/product1.jpg'"
                                :alt="item.name"
                                class="h-24 w-24 shrink-0 rounded-md object-cover"
                            >

                            <div class="min-w-0 flex-1">
                                <h3 class="truncate text-xl font-bold text-gray-900">{{ item.name }}</h3>
                                <p class="mt-1 text-lg text-gray-600">{{ item.price }}</p>

                                <div class="mt-4 flex items-center gap-5">
                                    <button
                                        type="button"
                                        @click="updateQuantity(item, item.quantity - 1)"
                                        class="flex h-9 w-9 items-center justify-center rounded-md border border-gray-200 text-2xl leading-none text-gray-900 shadow-sm hover:bg-gray-50"
                                        aria-label="Decrease quantity"
                                    >
                                        -
                                    </button>
                                    <span class="min-w-5 text-center text-xl font-medium text-gray-900">{{ item.quantity }}</span>
                                    <button
                                        type="button"
                                        @click="updateQuantity(item, item.quantity + 1)"
                                        class="flex h-9 w-9 items-center justify-center rounded-md border border-gray-200 text-2xl leading-none text-gray-900 shadow-sm hover:bg-gray-50"
                                        aria-label="Increase quantity"
                                    >
                                        +
                                    </button>
                                </div>
                            </div>

                            <button
                                type="button"
                                @click="removeItem(item)"
                                class="mt-16 flex h-8 w-8 shrink-0 items-center justify-center text-[#FF4D4D] hover:text-red-600"
                                aria-label="Remove item"
                            >
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0h8m-5-3h2a1 1 0 011 1v2h-4V5a1 1 0 011-1z" />
                                </svg>
                            </button>
                        </div>

                        <div class="space-y-4 border-b border-gray-200 py-8 text-xl text-gray-600">
                            <div class="flex items-center justify-between">
                                <span>Subtotal</span>
                                <span class="flex items-center gap-2">
                                    <img src="/images/espees_logo.png" alt="Espees" class="h-5 w-5 object-contain">
                                    {{ subtotal.toFixed(2) }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Shipping</span>
                                <span class="flex items-center gap-2">
                                    <img src="/images/espees_logo.png" alt="Espees" class="h-5 w-5 object-contain">
                                    {{ shipping.toFixed(2) }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between py-6 text-2xl font-bold text-gray-900">
                            <span>Total</span>
                            <span class="flex items-center gap-2">
                                <img src="/images/espees_logo.png" alt="Espees" class="h-5 w-5 object-contain">
                                {{ total.toFixed(2) }}
                            </span>
                        </div>

                        <button
                            type="submit"
                            form="checkout-form"
                            :disabled="isProcessing || !summaryItems.length"
                            class="mt-4 w-full rounded-lg bg-[#F9AD32] px-5 py-4 text-lg font-semibold text-black transition hover:bg-[#f3a11d] disabled:opacity-50"
                        >
                            {{ isProcessing ? 'Starting Payment...' : 'Complete Order' }}
                        </button>
                    </aside>
                </div>
            </section>
        </main>
    </div>
</template>
