<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    products: Object,
    filters: Object,
});

const form = useForm({
    search: props.filters.search || '',
});

const search = () => {
    router.get(route('admin.products.index'), {
        search: form.search,
    }, {
        preserveState: true,
        replace: true,
    });
};

const destroy = (product) => {
    if (!window.confirm(`Delete ${product.name}?`)) {
        return;
    }

    router.delete(route('admin.products.destroy', product.id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Admin Products" />

    <AdminLayout>
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-3xl font-black">Products</h1>
                <p class="mt-1 text-gray-500">Manage store products, pricing, images, and availability.</p>
            </div>
            <Link :href="route('admin.products.create')" class="rounded-lg bg-brand px-4 py-3 text-sm font-bold text-white transition hover:bg-brand-dark">
                Add Product
            </Link>
        </div>

        <form class="mt-8 flex flex-col gap-3 sm:flex-row" @submit.prevent="search">
            <input
                v-model="form.search"
                type="search"
                placeholder="Search products"
                class="h-12 rounded-lg border border-gray-300 px-4 text-gray-900 focus:border-brand focus:ring-brand sm:w-96"
            >
            <button class="h-12 rounded-lg bg-brand px-5 font-bold text-white transition hover:bg-brand-dark">Search</button>
        </form>

        <!-- Desktop table -->
        <div class="mt-6 hidden overflow-x-auto rounded-xl border border-gray-200 bg-white shadow-sm md:block">
            <table class="w-full min-w-[760px] text-left">
                <thead class="bg-navy text-white">
                    <tr>
                        <th class="px-4 py-4">Product</th>
                        <th class="px-4 py-4">Price</th>
                        <th class="px-4 py-4">Availability</th>
                        <th class="px-4 py-4">Created</th>
                        <th class="px-4 py-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="product in products.data" :key="product.id" class="border-b border-gray-200">
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-3">
                                <img :src="product.image_url || '/images/product1.jpg'" :alt="product.name" class="h-14 w-14 rounded-lg object-cover">
                                <div>
                                    <p class="font-bold">{{ product.name }}</p>
                                    <p class="line-clamp-1 text-sm text-gray-500">{{ product.description }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 font-black">{{ Number(product.price).toFixed(2) }}</td>
                        <td class="px-4 py-4">
                            <span class="rounded-full px-3 py-1 text-xs font-bold" :class="product.is_sold_out ? 'bg-gray-900 text-white' : 'border border-green-200 bg-green-50 text-green-700'">
                                {{ product.is_sold_out ? 'Sold Out' : 'Available' }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-500">{{ new Date(product.created_at).toLocaleDateString() }}</td>
                        <td class="px-4 py-4">
                            <div class="flex flex-wrap gap-2">
                                <Link :href="route('admin.products.edit', product.id)" class="rounded border border-gray-300 px-3 py-2 text-sm font-bold hover:bg-gray-50">
                                    Edit
                                </Link>
                                <button
                                    type="button"
                                    @click="router.patch(route('admin.products.toggle-sold-out', product.id), {}, { preserveScroll: true })"
                                    class="rounded border border-gray-300 px-3 py-2 text-sm font-bold hover:bg-gray-50"
                                >
                                    {{ product.is_sold_out ? 'Restock' : 'Sold Out' }}
                                </button>
                                <button type="button" @click="destroy(product)" class="rounded bg-danger px-3 py-2 text-sm font-bold text-white hover:bg-red-600">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="!products.data.length">
                        <td colspan="5" class="px-4 py-10 text-center text-gray-500">No products found.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Mobile cards -->
        <div class="mt-6 space-y-3 md:hidden">
            <div v-for="product in products.data" :key="product.id" class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <div class="flex items-center gap-3">
                    <img :src="product.image_url || '/images/product1.jpg'" :alt="product.name" class="h-14 w-14 shrink-0 rounded-lg object-cover">
                    <div class="min-w-0 flex-1">
                        <p class="truncate font-bold">{{ product.name }}</p>
                        <p class="line-clamp-1 text-sm text-gray-500">{{ product.description }}</p>
                    </div>
                    <span class="shrink-0 rounded-full px-3 py-1 text-xs font-bold" :class="product.is_sold_out ? 'bg-gray-900 text-white' : 'border border-green-200 bg-green-50 text-green-700'">
                        {{ product.is_sold_out ? 'Sold Out' : 'Available' }}
                    </span>
                </div>
                <div class="mt-3 flex items-center justify-between text-sm text-gray-500">
                    <span>{{ new Date(product.created_at).toLocaleDateString() }}</span>
                    <span class="font-black text-gray-900">{{ Number(product.price).toFixed(2) }}</span>
                </div>
                <div class="mt-3 flex flex-wrap gap-2">
                    <Link :href="route('admin.products.edit', product.id)" class="rounded border border-gray-300 px-3 py-2 text-sm font-bold hover:bg-gray-50">
                        Edit
                    </Link>
                    <button
                        type="button"
                        @click="router.patch(route('admin.products.toggle-sold-out', product.id), {}, { preserveScroll: true })"
                        class="rounded border border-gray-300 px-3 py-2 text-sm font-bold hover:bg-gray-50"
                    >
                        {{ product.is_sold_out ? 'Restock' : 'Sold Out' }}
                    </button>
                    <button type="button" @click="destroy(product)" class="rounded bg-danger px-3 py-2 text-sm font-bold text-white hover:bg-red-600">
                        Delete
                    </button>
                </div>
            </div>
            <p v-if="!products.data.length" class="py-10 text-center text-gray-500">No products found.</p>
        </div>

        <div class="mt-6 flex flex-wrap gap-2">
            <Link
                v-for="link in products.links"
                :key="link.label"
                :href="link.url || '#'"
                class="rounded border border-gray-300 px-3 py-2 text-sm font-bold"
                :class="{ 'border-brand bg-brand text-white': link.active, 'pointer-events-none opacity-40': !link.url }"
                v-html="link.label"
            />
        </div>
    </AdminLayout>
</template>
