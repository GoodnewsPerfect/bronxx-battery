<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const page = usePage();
const sidebarOpen = ref(false);
const flash = computed(() => page.props.flash ?? {});

const navItems = [
    { label: 'Dashboard', route: 'admin.dashboard' },
    { label: 'Products', route: 'admin.products.index' },
    { label: 'Add Product', route: 'admin.products.create' },
    { label: 'Orders', route: 'admin.orders.index' },
];

const isActive = (routeName) => route().current(routeName);
</script>

<template>
    <div class="min-h-screen bg-gray-50 text-gray-900">
        <button
            type="button"
            class="fixed left-4 top-4 z-50 flex h-11 items-center rounded border border-navy bg-white px-3 text-sm font-bold shadow-sm lg:hidden"
            @click="sidebarOpen = !sidebarOpen"
        >
            Menu
        </button>

        <aside
            class="fixed inset-y-0 left-0 z-40 flex w-72 flex-col border-r border-navy bg-navy px-5 py-6 text-white transition-transform lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <div class="mb-10 flex items-center gap-3">
                <img src="/images/Bronx_Logo_2-removebg-preview.png" alt="Bronx Batteries" class="h-9 w-auto">
                <div>
                    <p class="text-lg font-black tracking-tight">Admin</p>
                    <p class="text-xs text-white/60">Store management</p>
                </div>
            </div>

            <nav class="flex-1 space-y-2">
                <Link
                    v-for="item in navItems"
                    :key="item.route"
                    :href="route(item.route)"
                    class="block rounded-lg px-4 py-3 text-sm font-bold transition"
                    :class="isActive(item.route) ? 'bg-brand text-white' : 'text-white/80 hover:bg-white/10 hover:text-white'"
                    @click="sidebarOpen = false"
                >
                    {{ item.label }}
                </Link>
            </nav>

            <Link
                :href="route('admin.logout')"
                method="post"
                as="button"
                class="rounded-lg border border-white/30 px-4 py-3 text-left text-sm font-bold text-white transition hover:bg-white hover:text-navy"
            >
                Logout
            </Link>
        </aside>

        <main class="min-h-screen lg:pl-72">
            <div class="mx-auto max-w-7xl px-4 py-8 pt-20 sm:px-6 lg:px-8 lg:pt-8">
                <div v-if="flash.success" class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm font-bold text-green-800">
                    {{ flash.success }}
                </div>
                <div v-if="flash.error" class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm font-bold text-red-800">
                    {{ flash.error }}
                </div>

                <slot />
            </div>
        </main>
    </div>
</template>
