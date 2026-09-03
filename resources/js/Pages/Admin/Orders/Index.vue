<script setup>
import { Head, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    orders: Object,
    filters: Object,
});

const search = ref(props.filters.search || '');
const paymentStatus = ref(props.filters.payment_status || '');
const selectedOrder = ref(null);

const applyFilters = () => {
    router.get(route('admin.orders.index'), {
        search: search.value,
        payment_status: paymentStatus.value,
    }, {
        preserveState: true,
        replace: true,
    });
};

const orderCode = (order) => `ORD-${String(order.id).padStart(10, '0')}`;
const items = computed(() => selectedOrder.value?.items ?? []);
const subtotal = computed(() => items.value.reduce((sum, item) => sum + Number(item.subtotal), 0));
</script>

<template>
    <Head title="Admin Orders" />

    <AdminLayout>
        <div>
            <h1 class="text-3xl font-black">Orders</h1>
            <p class="mt-1 text-gray-500">Review customers, products ordered, and payment status.</p>
        </div>

        <form class="mt-8 flex flex-col gap-3 lg:flex-row" @submit.prevent="applyFilters">
            <input v-model="search" type="search" placeholder="Search customer, email, order ID, reference" class="h-12 rounded-lg border border-gray-300 px-4 focus:border-brand focus:ring-brand lg:w-96">
            <select v-model="paymentStatus" class="h-12 rounded-lg border border-gray-300 px-4 focus:border-brand focus:ring-brand">
                <option value="">All payment statuses</option>
                <option value="paid">Successful</option>
                <option value="pending">Pending/Unsuccessful</option>
                <option value="failed">Failed</option>
            </select>
            <button class="h-12 rounded-lg bg-brand px-5 font-bold text-white transition hover:bg-brand-dark">Filter</button>
        </form>

        <!-- Desktop table -->
        <div class="mt-6 hidden overflow-x-auto rounded-xl border border-gray-200 bg-white shadow-sm md:block">
            <table class="w-full min-w-[880px] text-left">
                <thead class="bg-navy text-white">
                    <tr>
                        <th class="px-4 py-4">Order</th>
                        <th class="px-4 py-4">Customer</th>
                        <th class="px-4 py-4">Total</th>
                        <th class="px-4 py-4">Payment</th>
                        <th class="px-4 py-4">Date</th>
                        <th class="px-4 py-4">Status</th>
                        <th class="px-4 py-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="order in orders.data" :key="order.id" class="border-b border-gray-200">
                        <td class="px-4 py-4 font-bold">{{ orderCode(order) }}</td>
                        <td class="px-4 py-4">
                            <p class="font-bold">{{ order.user?.name || 'Customer' }}</p>
                            <p class="text-sm text-gray-500">{{ order.user?.email || 'No email' }}</p>
                        </td>
                        <td class="px-4 py-4 font-black">{{ Number(order.total_amount).toFixed(2) }}</td>
                        <td class="px-4 py-4">
                            <span class="rounded-full px-3 py-1 text-xs font-bold" :class="order.payment_status === 'paid' ? 'border border-green-200 bg-green-50 text-green-700' : 'bg-gray-900 text-white'">
                                {{ order.payment_status }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-500">{{ new Date(order.created_at).toLocaleDateString() }}</td>
                        <td class="px-4 py-4">
                            <select
                                :value="order.status"
                                class="rounded border border-gray-300 text-sm focus:border-brand focus:ring-brand"
                                @change="router.patch(route('admin.orders.status', order.id), { status: $event.target.value }, { preserveScroll: true })"
                            >
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </td>
                        <td class="px-4 py-4">
                            <button type="button" class="rounded border border-gray-300 px-3 py-2 text-sm font-bold hover:bg-gray-50" @click="selectedOrder = order">
                                View
                            </button>
                        </td>
                    </tr>
                    <tr v-if="!orders.data.length">
                        <td colspan="7" class="px-4 py-10 text-center text-gray-500">No orders found.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Mobile cards -->
        <div class="mt-6 space-y-3 md:hidden">
            <div v-for="order in orders.data" :key="order.id" class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="font-bold">{{ orderCode(order) }}</p>
                        <p class="text-sm text-gray-500">{{ order.user?.name || 'Customer' }}</p>
                    </div>
                    <span class="rounded-full px-3 py-1 text-xs font-bold" :class="order.payment_status === 'paid' ? 'border border-green-200 bg-green-50 text-green-700' : 'bg-gray-900 text-white'">
                        {{ order.payment_status }}
                    </span>
                </div>
                <div class="mt-3 flex items-center justify-between text-sm text-gray-500">
                    <span>{{ new Date(order.created_at).toLocaleDateString() }}</span>
                    <span class="font-black text-gray-900">{{ Number(order.total_amount).toFixed(2) }}</span>
                </div>
                <div class="mt-3 flex items-center gap-2">
                    <select
                        :value="order.status"
                        class="h-10 flex-1 rounded border border-gray-300 text-sm focus:border-brand focus:ring-brand"
                        @change="router.patch(route('admin.orders.status', order.id), { status: $event.target.value }, { preserveScroll: true })"
                    >
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                    <button type="button" class="h-10 rounded border border-gray-300 px-3 text-sm font-bold hover:bg-gray-50" @click="selectedOrder = order">
                        View
                    </button>
                </div>
            </div>
            <p v-if="!orders.data.length" class="py-10 text-center text-gray-500">No orders found.</p>
        </div>

        <div class="mt-6 flex flex-wrap gap-2">
            <a
                v-for="link in orders.links"
                :key="link.label"
                :href="link.url || '#'"
                class="rounded border border-gray-300 px-3 py-2 text-sm font-bold"
                :class="{ 'border-brand bg-brand text-white': link.active, 'pointer-events-none opacity-40': !link.url }"
                v-html="link.label"
            />
        </div>

        <Modal :show="Boolean(selectedOrder)" @close="selectedOrder = null" maxWidth="2xl">
            <div v-if="selectedOrder" class="bg-white p-6 sm:p-8">
                <h2 class="text-2xl font-black">Order Details</h2>
                <p class="mt-2 text-gray-500">{{ orderCode(selectedOrder) }}</p>

                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div>
                        <p class="text-sm font-bold text-gray-500">Customer</p>
                        <p class="font-black">{{ selectedOrder.user?.name }}</p>
                        <p class="text-sm text-gray-500">{{ selectedOrder.user?.email }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-500">Payment</p>
                        <p class="font-black">{{ selectedOrder.payment_status }}</p>
                    </div>
                </div>

                <div class="mt-6 space-y-3">
                    <div v-for="item in items" :key="item.id" class="flex items-center gap-4 rounded-xl border border-gray-200 p-4">
                        <img :src="item.product_image || '/images/product1.jpg'" :alt="item.product_name" class="h-16 w-16 rounded-lg object-cover">
                        <div class="flex-1">
                            <p class="font-black">{{ item.product_name }}</p>
                            <p class="text-sm text-gray-500">Quantity: {{ item.quantity }} × {{ Number(item.price).toFixed(2) }}</p>
                        </div>
                        <p class="font-black">{{ Number(item.subtotal).toFixed(2) }}</p>
                    </div>
                </div>

                <div class="mt-6 border-t border-gray-200 pt-4">
                    <div class="flex justify-between text-sm text-gray-500">
                        <span>Subtotal</span>
                        <span>{{ subtotal.toFixed(2) }}</span>
                    </div>
                    <div class="mt-3 flex justify-between text-2xl font-black">
                        <span>Total</span>
                        <span>{{ Number(selectedOrder.total_amount).toFixed(2) }}</span>
                    </div>
                </div>
            </div>
        </Modal>
    </AdminLayout>
</template>
