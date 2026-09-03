<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';
import Modal from '@/Components/Modal.vue';
import { formatEspees } from '@/Support/pricing.js';

const props = defineProps({
    orders: {
        type: Array,
        default: () => [],
    },
});

const searchQuery = ref('');
const statusFilter = ref('all');
const sortBy = ref('newest');
const selectedOrder = ref(null);
const isDetailsOpen = ref(false);
const isPaymentStarting = ref(false);
const paymentActionError = ref('');

const filters = [
    { label: 'All', value: 'all' },
    { label: 'Successful', value: 'completed' },
    { label: 'Failed', value: 'cancelled' },
    { label: 'Processing', value: 'pending' },
];

const orderCode = (order) => `ORD-${String(order.id).padStart(10, '0')}`;

const openDetails = (order) => {
    selectedOrder.value = order;
    paymentActionError.value = '';
    isDetailsOpen.value = true;
};

const closeDetails = () => {
    isDetailsOpen.value = false;
    setTimeout(() => {
        selectedOrder.value = null;
    }, 200);
};

const formatDate = (value) => new Intl.DateTimeFormat('en-GB', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
}).format(new Date(value));

const titleCase = (value) => String(value ?? '')
    .replace(/_/g, ' ')
    .replace(/\b\w/g, (letter) => letter.toUpperCase());

const statusClass = (status) => ({
    pending: 'bg-orange-50 text-orange-600',
    processing: 'bg-orange-50 text-orange-600',
    completed: 'bg-green-50 text-green-700',
    paid: 'bg-green-50 text-green-700',
    cancelled: 'bg-red-50 text-red-700',
    canceled: 'bg-red-50 text-red-700',
    failed: 'bg-red-50 text-red-700',
}[status] ?? 'bg-gray-100 text-gray-700');

const orderItems = computed(() => selectedOrder.value?.items ?? []);

const orderSubtotal = computed(() => orderItems.value.reduce(
    (total, item) => total + Number(item.subtotal ?? (Number(item.price) * Number(item.quantity))),
    0,
));

const canCompleteSelectedOrder = computed(() => {
    if (!selectedOrder.value) {
        return false;
    }

    return selectedOrder.value.payment_status !== 'paid';
});

const startOrderPayment = () => {
    if (!selectedOrder.value || isPaymentStarting.value) {
        return;
    }

    isPaymentStarting.value = true;
    paymentActionError.value = '';

    const orderId = String(selectedOrder.value.id);
    const successUrl = new URL(route('order.confirmation', orderId, false), window.location.origin);
    const failUrl = new URL(route('order.confirmation', orderId, false), window.location.origin);

    successUrl.searchParams.set('espees_return', 'success');
    failUrl.searchParams.set('espees_return', 'failed');

    window.axios.post(route('payment.initialize-espees'), {
        orderId,
        amount: selectedOrder.value.total_amount,
        description: `Order #${orderId}`,
        successUrl: successUrl.href,
        failUrl: failUrl.href,
    }, {
        headers: {
            Accept: 'application/json',
        },
    }).then(({ data }) => {
        if (data.status === 'success' && data.payment_url) {
            window.location.href = data.payment_url;
            return;
        }

        paymentActionError.value = data.message || 'Unable to start Espees payment. Please try again.';
    }).catch((error) => {
        paymentActionError.value = error.response?.data?.message
            || error.response?.data?.status_details
            || 'Unable to start Espees payment. Please try again.';
    }).finally(() => {
        isPaymentStarting.value = false;
    });
};

const filteredOrders = computed(() => {
    const query = searchQuery.value.trim().toLowerCase();

    return [...(props.orders ?? [])]
        .filter((order) => statusFilter.value === 'all' || order.status === statusFilter.value)
        .filter((order) => {
            if (!query) {
                return true;
            }

            return orderCode(order).toLowerCase().includes(query)
                || String(order.total_amount).includes(query)
                || String(order.status).toLowerCase().includes(query);
        })
        .sort((a, b) => {
            if (sortBy.value === 'oldest') {
                return new Date(a.created_at) - new Date(b.created_at);
            }

            if (sortBy.value === 'highest') {
                return Number(b.total_amount) - Number(a.total_amount);
            }

            return new Date(b.created_at) - new Date(a.created_at);
        });
});
</script>

<template>
    <Head title="Orders" />

    <StorefrontLayout :newsletter="false">
        <div class="flex-1 bg-[#F4F7FB] py-12 sm:py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid gap-8 lg:grid-cols-[220px_1fr]">
                    <aside class="pt-2">
                        <nav class="flex gap-2 overflow-x-auto pb-2 text-sm font-medium lg:flex-col lg:gap-1 lg:overflow-visible lg:pb-0 lg:text-base">
                            <Link :href="route('dashboard')" class="shrink-0 rounded-lg px-5 py-3 text-gray-900 hover:bg-white">
                                My Profile
                            </Link>
                            <Link :href="route('orders.index')" class="shrink-0 rounded-lg bg-brand px-5 py-3 text-white">
                                Orders
                            </Link>
                            <Link :href="route('logout')" method="post" as="button" class="shrink-0 rounded-lg px-5 py-3 text-left text-gray-900 hover:bg-white">
                                Logout
                            </Link>
                        </nav>
                    </aside>

                    <section class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:p-8">
                        <div class="mb-8 flex items-center gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-brand/10 text-brand sm:h-14 sm:w-14">
                                <svg class="h-6 w-6 sm:h-7 sm:w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 14h6m-6-4h6m-7 10h8a2 2 0 002-2V6.5L13.5 2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h1 class="text-xl font-bold text-gray-900 sm:text-2xl">My Orders</h1>
                        </div>

                        <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                            <div class="flex flex-wrap gap-2">
                                <button
                                    v-for="filter in filters"
                                    :key="filter.value"
                                    @click="statusFilter = filter.value"
                                    class="rounded-lg border px-4 py-2 text-sm font-medium shadow-sm transition"
                                    :class="statusFilter === filter.value ? 'border-brand bg-brand text-white' : 'border-gray-200 bg-white text-gray-900 hover:bg-gray-50'"
                                >
                                    {{ filter.label }}
                                </button>
                            </div>

                            <div class="flex flex-col gap-3 sm:flex-row">
                                <label class="relative block">
                                    <span class="sr-only">Search orders</span>
                                    <svg class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    <input
                                        v-model="searchQuery"
                                        type="search"
                                        placeholder="Search"
                                        class="h-11 w-full rounded-lg border border-gray-200 pl-11 pr-4 text-sm shadow-sm focus:border-brand focus:ring-brand sm:w-64"
                                    >
                                </label>

                                <select
                                    v-model="sortBy"
                                    class="h-11 rounded-lg border border-gray-200 px-4 text-sm font-medium shadow-sm focus:border-brand focus:ring-brand"
                                >
                                    <option value="newest">Sort by</option>
                                    <option value="oldest">Oldest</option>
                                    <option value="highest">Highest total</option>
                                </select>
                            </div>
                        </div>

                        <!-- Desktop table -->
                        <div class="hidden overflow-x-auto md:block">
                            <table class="w-full min-w-[640px] text-left">
                                <thead>
                                    <tr class="border-b border-gray-200 text-sm font-semibold text-gray-900">
                                        <th class="px-3 py-4">Order ID</th>
                                        <th class="px-3 py-4">Date</th>
                                        <th class="px-3 py-4">Total cost</th>
                                        <th class="px-3 py-4">Status</th>
                                        <th class="px-3 py-4">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="order in filteredOrders"
                                        :key="order.id"
                                        class="border-b border-dashed border-gray-200 text-sm"
                                    >
                                        <td class="px-3 py-5 font-medium">{{ orderCode(order) }}</td>
                                        <td class="px-3 py-5">{{ formatDate(order.created_at) }}</td>
                                        <td class="px-3 py-5 font-bold">{{ formatEspees(order.total_amount) }}</td>
                                        <td class="px-3 py-5">
                                            <span class="rounded-lg px-3 py-1.5 text-xs font-bold" :class="statusClass(order.status)">
                                                {{ titleCase(order.status) }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-5">
                                            <button
                                                type="button"
                                                @click="openDetails(order)"
                                                class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-medium shadow-sm hover:bg-gray-50"
                                            >
                                                View
                                            </button>
                                        </td>
                                    </tr>

                                    <tr v-if="!filteredOrders.length">
                                        <td colspan="5" class="px-3 py-12 text-center text-gray-500">
                                            No orders.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile cards -->
                        <div class="space-y-3 md:hidden">
                            <button
                                v-for="order in filteredOrders"
                                :key="order.id"
                                type="button"
                                @click="openDetails(order)"
                                class="block w-full rounded-xl border border-gray-200 p-4 text-left shadow-sm"
                            >
                                <div class="flex items-center justify-between gap-3">
                                    <span class="font-semibold text-gray-900">{{ orderCode(order) }}</span>
                                    <span class="rounded-lg px-2.5 py-1 text-xs font-bold" :class="statusClass(order.status)">
                                        {{ titleCase(order.status) }}
                                    </span>
                                </div>
                                <div class="mt-2 flex items-center justify-between text-sm text-gray-500">
                                    <span>{{ formatDate(order.created_at) }}</span>
                                    <span class="font-bold text-gray-900">{{ formatEspees(order.total_amount) }} Espees</span>
                                </div>
                            </button>

                            <p v-if="!filteredOrders.length" class="py-12 text-center text-gray-500">No orders.</p>
                        </div>
                    </section>
                </div>
            </div>
        </div>

        <Modal :show="isDetailsOpen" @close="closeDetails" maxWidth="2xl">
            <div v-if="selectedOrder" class="relative bg-white p-5 sm:p-8">
                <button
                    type="button"
                    @click="closeDetails"
                    class="absolute right-4 top-4 flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-gray-500 shadow-sm hover:bg-gray-50 hover:text-gray-900"
                    aria-label="Close order details"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <div class="pr-10">
                    <h2 class="text-xl font-bold text-gray-900 sm:text-2xl">Order Details</h2>
                    <p class="mt-2 text-sm text-gray-500 sm:text-base">Order #{{ orderCode(selectedOrder) }}</p>
                </div>

                <div class="mt-6 grid gap-6 sm:grid-cols-2">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Order Number</p>
                        <p class="mt-1 font-bold text-gray-900">{{ orderCode(selectedOrder) }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Order Date</p>
                        <p class="mt-1 font-bold text-gray-900">{{ formatDate(selectedOrder.created_at) }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Status</p>
                        <span class="mt-2 inline-flex rounded-lg px-3 py-1.5 text-sm font-bold" :class="statusClass(selectedOrder.status)">
                            {{ titleCase(selectedOrder.status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Payment Status</p>
                        <p class="mt-1 font-bold text-gray-900">{{ titleCase(selectedOrder.payment_status) }}</p>
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="text-lg font-bold text-gray-900">Order Items</h3>

                    <div class="mt-4 space-y-3">
                        <div
                            v-for="item in orderItems"
                            :key="item.id"
                            class="flex items-center gap-4 rounded-xl border border-gray-200 p-4"
                        >
                            <img
                                :src="item.product_image || '/images/product1.jpg'"
                                :alt="item.product_name"
                                class="h-16 w-16 shrink-0 rounded-lg object-cover sm:h-20 sm:w-20"
                            >

                            <div class="min-w-0 flex-1">
                                <h4 class="truncate font-bold text-gray-900">{{ item.product_name }}</h4>
                                <p class="mt-1 text-sm text-gray-500">
                                    Quantity: {{ item.quantity }} × {{ formatEspees(item.price) }}
                                </p>
                            </div>

                            <p class="font-bold text-gray-900">{{ formatEspees(item.subtotal) }}</p>
                        </div>

                        <div v-if="!orderItems.length" class="rounded-xl border border-dashed border-gray-200 p-6 text-center text-gray-500">
                            No items found for this order.
                        </div>
                    </div>
                </div>

                <div class="mt-8 space-y-3 border-t border-gray-200 pt-6">
                    <div class="flex items-center justify-between text-sm text-gray-600">
                        <span>Subtotal</span>
                        <span class="text-gray-900">{{ formatEspees(orderSubtotal) }}</span>
                    </div>
                    <div class="flex items-center justify-between border-t border-gray-200 pt-4 text-xl font-bold text-gray-900">
                        <span>Total</span>
                        <span>{{ formatEspees(selectedOrder.total_amount) }}</span>
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="text-lg font-bold text-gray-900">Delivery Address</h3>
                    <p class="mt-3 text-sm text-gray-500">
                        {{ selectedOrder.shipping_address || 'No delivery address provided.' }}
                    </p>
                </div>

                <div class="mt-8 border-t border-gray-200 pt-6">
                    <p v-if="paymentActionError" class="mb-3 text-sm text-red-600">
                        {{ paymentActionError }}
                    </p>
                    <button
                        v-if="canCompleteSelectedOrder"
                        type="button"
                        @click="startOrderPayment"
                        :disabled="isPaymentStarting"
                        class="inline-flex w-full items-center justify-center rounded-lg bg-accent px-5 py-3 text-base font-semibold text-black shadow-sm transition hover:bg-accent-dark disabled:opacity-50 sm:w-auto"
                    >
                        {{ isPaymentStarting ? 'Starting Payment...' : 'Complete Order' }}
                    </button>
                </div>
            </div>
        </Modal>
    </StorefrontLayout>
</template>
