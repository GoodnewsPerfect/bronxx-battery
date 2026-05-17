<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineProps({
    stats: Object,
    recentOrders: Array,
    recentProducts: Array,
});

const statLabels = {
    total_products: 'Total Products',
    total_orders: 'Total Orders',
    successful_payments: 'Successful Payments',
    failed_or_pending_payments: 'Failed/Pending Payments',
    sold_out_products: 'Sold Out Products',
};

const orderCode = (order) => `ORD-${String(order.id).padStart(10, '0')}`;
</script>

<template>
    <Head title="Admin Dashboard" />

    <AdminLayout>
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-3xl font-black">Dashboard</h1>
                <p class="mt-1 text-gray-500">Store overview and recent activity.</p>
            </div>
            <Link :href="route('admin.products.create')" class="rounded-lg bg-black px-4 py-3 text-sm font-bold text-white">
                Add Product
            </Link>
        </div>

        <section class="mt-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
            <div v-for="(value, key) in stats" :key="key" class="rounded-xl border border-black bg-white p-5">
                <p class="text-sm font-bold text-gray-500">{{ statLabels[key] }}</p>
                <p class="mt-3 text-3xl font-black">{{ value }}</p>
            </div>
        </section>

        <section class="mt-8 grid gap-6 lg:grid-cols-2">
            <div class="rounded-xl border border-black bg-white p-5">
                <div class="mb-5 flex items-center justify-between">
                    <h2 class="text-xl font-black">Recent Orders</h2>
                    <Link :href="route('admin.orders.index')" class="text-sm font-bold underline">View all</Link>
                </div>
                <div class="space-y-3">
                    <div v-for="order in recentOrders" :key="order.id" class="flex items-center justify-between border-b border-gray-200 pb-3">
                        <div>
                            <p class="font-bold">{{ orderCode(order) }}</p>
                            <p class="text-sm text-gray-500">{{ order.user?.name || 'Customer' }} · {{ order.payment_status }}</p>
                        </div>
                        <p class="font-black">{{ Number(order.total_amount).toFixed(2) }}</p>
                    </div>
                    <p v-if="!recentOrders.length" class="text-sm text-gray-500">No orders yet.</p>
                </div>
            </div>

            <div class="rounded-xl border border-black bg-white p-5">
                <div class="mb-5 flex items-center justify-between">
                    <h2 class="text-xl font-black">Recent Products</h2>
                    <Link :href="route('admin.products.index')" class="text-sm font-bold underline">View all</Link>
                </div>
                <div class="space-y-3">
                    <div v-for="product in recentProducts" :key="product.id" class="flex items-center justify-between border-b border-gray-200 pb-3">
                        <div>
                            <p class="font-bold">{{ product.name }}</p>
                            <p class="text-sm text-gray-500">{{ product.is_sold_out ? 'Sold Out' : 'Available' }}</p>
                        </div>
                        <p class="font-black">{{ Number(product.price).toFixed(2) }}</p>
                    </div>
                    <p v-if="!recentProducts.length" class="text-sm text-gray-500">No products yet.</p>
                </div>
            </div>
        </section>
    </AdminLayout>
</template>
