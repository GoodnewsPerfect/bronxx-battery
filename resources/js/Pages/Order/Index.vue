<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import Header from '@/Components/Header.vue';
import Sidebar from '@/Components/Sidebar.vue';
import Footer from '@/Components/Footer.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    orders: Array,
});

const page = usePage();
const cart = computed(() => page.props.cart ?? { item_count: 0 });
const isSidebarOpen = ref(false);
const searchQuery = ref('');
const statusFilter = ref('all');
const sortBy = ref('newest');
const selectedOrder = ref(null);
const isDetailsOpen = ref(false);

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

const filters = [
    { label: 'All', value: 'all' },
    { label: 'Successful', value: 'completed' },
    { label: 'Failed', value: 'cancelled' },
    { label: 'Processing', value: 'pending' },
];

const orderCode = (order) => `ORD-${String(order.id).padStart(10, '0')}`;

const openDetails = (order) => {
    selectedOrder.value = order;
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

const filteredOrders = computed(() => {
    const query = searchQuery.value.trim().toLowerCase();

    return [...props.orders]
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

    <div class="min-h-screen bg-[#F4F7FB] text-gray-900 font-sans flex flex-col pt-28">
        <Sidebar :open="isSidebarOpen" />
        <Header :cart="cart" @toggle-sidebar="toggleSidebar" />

        <main class="flex-1 py-28">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid gap-8 lg:grid-cols-[220px_1fr]">
                    <aside class="pt-2">
                        <nav class="space-y-4 text-lg font-medium">
                            <Link :href="route('dashboard')" class="block rounded-lg px-6 py-3 text-gray-900 hover:bg-white">
                                My Profile
                            </Link>
                            <Link :href="route('orders.index')" class="block rounded-lg bg-[#2456C6] px-6 py-3 text-white">
                                Orders
                            </Link>
                            <button class="block w-full rounded-lg px-6 py-3 text-left text-gray-900 hover:bg-white">
                                Security
                            </button>
                            <Link :href="route('logout')" method="post" as="button" class="block w-full rounded-lg px-6 py-3 text-left text-gray-900 hover:bg-white">
                                Logout
                            </Link>
                        </nav>
                    </aside>

                    <section class="rounded-xl border border-gray-200 bg-white p-8 shadow-sm">
                        <div class="mb-10 flex items-center gap-4">
                            <div class="flex h-14 w-14 items-center justify-center rounded-full bg-[#EEF2FF] text-[#2456C6]">
                                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 14h6m-6-4h6m-7 10h8a2 2 0 002-2V6.5L13.5 2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h1 class="text-2xl font-bold text-gray-900">Receipt</h1>
                        </div>

                        <div class="mb-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                            <div class="flex flex-wrap gap-3">
                                <button
                                    v-for="filter in filters"
                                    :key="filter.value"
                                    @click="statusFilter = filter.value"
                                    class="rounded-lg border px-5 py-2.5 text-lg font-medium shadow-sm transition"
                                    :class="statusFilter === filter.value ? 'border-[#2456C6] bg-[#2456C6] text-white' : 'border-gray-200 bg-white text-gray-900 hover:bg-gray-50'"
                                >
                                    {{ filter.label }}
                                </button>
                            </div>

                            <div class="flex flex-col gap-3 sm:flex-row">
                                <label class="relative block">
                                    <svg class="absolute left-4 top-1/2 h-6 w-6 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    <input
                                        v-model="searchQuery"
                                        type="search"
                                        placeholder="Search"
                                        class="h-12 w-full rounded-lg border border-gray-200 pl-12 pr-4 text-lg shadow-sm focus:border-[#2456C6] focus:ring-[#2456C6] sm:w-80"
                                    >
                                </label>

                                <select
                                    v-model="sortBy"
                                    class="h-12 rounded-lg border border-gray-200 px-4 text-lg font-medium shadow-sm focus:border-[#2456C6] focus:ring-[#2456C6]"
                                >
                                    <option value="newest">Sort by</option>
                                    <option value="oldest">Oldest</option>
                                    <option value="highest">Highest total</option>
                                </select>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[760px] text-left">
                                <thead>
                                    <tr class="border-b border-gray-200 text-lg font-semibold text-gray-900">
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
                                        class="border-b border-dashed border-gray-200 text-lg"
                                    >
                                        <td class="px-3 py-5 font-medium">{{ orderCode(order) }}</td>
                                        <td class="px-3 py-5">{{ formatDate(order.created_at) }}</td>
                                        <td class="px-3 py-5 font-bold">{{ Number(order.total_amount).toFixed(2) }}</td>
                                        <td class="px-3 py-5">
                                            <span class="rounded-lg bg-orange-50 px-3 py-1.5 text-sm font-bold text-orange-600">
                                                {{ titleCase(order.status) }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-5">
                                            <button
                                                type="button"
                                                @click="openDetails(order)"
                                                class="inline-flex items-center gap-3 rounded-lg border border-gray-200 bg-white px-4 py-2 font-medium shadow-sm hover:bg-gray-50"
                                            >
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 12s3.75-6 9.75-6 9.75 6 9.75 6-3.75 6-9.75 6-9.75-6-9.75-6z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15a3 3 0 100-6 3 3 0 000 6z" />
                                                </svg>
                                                View
                                            </button>
                                        </td>
                                    </tr>

                                    <tr v-if="!filteredOrders.length">
                                        <td colspan="5" class="px-3 py-12 text-center text-gray-500">
                                            No orders found.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>
            </div>
        </main>

        <Modal :show="isDetailsOpen" @close="closeDetails" maxWidth="2xl">
            <div v-if="selectedOrder" class="relative bg-white p-6 sm:p-8">
                <button
                    type="button"
                    @click="closeDetails"
                    class="absolute right-4 top-4 flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 text-gray-500 shadow-sm hover:bg-gray-50 hover:text-gray-900"
                    aria-label="Close order details"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <div class="pr-10">
                    <h2 class="text-2xl font-bold text-gray-900">Order Details</h2>
                    <p class="mt-3 text-lg text-gray-500">Order #{{ orderCode(selectedOrder) }}</p>
                </div>

                <div class="mt-7 grid gap-6 sm:grid-cols-2">
                    <div>
                        <p class="text-base font-medium text-gray-500">Order Number</p>
                        <p class="mt-1 text-lg font-bold text-gray-900">{{ orderCode(selectedOrder) }}</p>
                    </div>
                    <div>
                        <p class="text-base font-medium text-gray-500">Order Date</p>
                        <p class="mt-1 text-lg font-bold text-gray-900">{{ formatDate(selectedOrder.created_at) }}</p>
                    </div>
                    <div>
                        <p class="text-base font-medium text-gray-500">Status</p>
                        <span class="mt-2 inline-flex rounded-lg px-3 py-1.5 text-sm font-bold" :class="statusClass(selectedOrder.status)">
                            {{ titleCase(selectedOrder.status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-base font-medium text-gray-500">Payment Status</p>
                        <p class="mt-1 text-lg font-bold text-gray-900">{{ titleCase(selectedOrder.payment_status) }}</p>
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
                                class="h-20 w-20 shrink-0 rounded-lg object-cover"
                            >

                            <div class="min-w-0 flex-1">
                                <h4 class="truncate text-lg font-bold text-gray-900">{{ item.product_name }}</h4>
                                <p class="mt-1 text-base text-gray-500">
                                    Quantity: {{ item.quantity }} × {{ Number(item.price).toFixed(2) }}
                                </p>
                            </div>

                            <p class="text-lg font-bold text-gray-900">{{ Number(item.subtotal).toFixed(2) }}</p>
                        </div>

                        <div v-if="!orderItems.length" class="rounded-xl border border-dashed border-gray-200 p-6 text-center text-gray-500">
                            No items found for this order.
                        </div>
                    </div>
                </div>

                <div class="mt-8 space-y-4 border-t border-gray-200 pt-6">
                    <div class="flex items-center justify-between text-base text-gray-600">
                        <span>Subtotal</span>
                        <span class="text-gray-900">{{ orderSubtotal.toFixed(2) }}</span>
                    </div>
                    <div class="flex items-center justify-between border-t border-gray-200 pt-4 text-2xl font-bold text-gray-900">
                        <span>Total</span>
                        <span>{{ Number(selectedOrder.total_amount).toFixed(2) }}</span>
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="text-lg font-bold text-gray-900">Delivery Address</h3>
                    <p class="mt-4 text-base text-gray-500">
                        {{ selectedOrder.shipping_address || 'No delivery address provided.' }}
                    </p>
                </div>
            </div>
        </Modal>

        <Footer />
    </div>
</template>
