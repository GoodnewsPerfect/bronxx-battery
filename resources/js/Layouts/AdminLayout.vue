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
    <div class="min-h-screen bg-white text-black">
        <button
            type="button"
            class="fixed left-4 top-4 z-50 rounded border border-black bg-white px-3 py-2 text-sm font-bold lg:hidden"
            @click="sidebarOpen = !sidebarOpen"
        >
            Menu
        </button>

        <aside
            class="fixed inset-y-0 left-0 z-40 flex w-72 flex-col border-r border-black bg-black px-5 py-6 text-white transition-transform lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <div class="mb-10">
                <p class="text-2xl font-black tracking-tight">Bronx Admin</p>
                <p class="mt-1 text-sm text-white/60">Store management</p>
            </div>

            <nav class="flex-1 space-y-2">
                <Link
                    v-for="item in navItems"
                    :key="item.route"
                    :href="route(item.route)"
                    class="block rounded-lg px-4 py-3 text-sm font-bold transition"
                    :class="isActive(item.route) ? 'bg-white text-black' : 'text-white hover:bg-white/10'"
                    @click="sidebarOpen = false"
                >
                    {{ item.label }}
                </Link>
            </nav>

            <Link
                :href="route('admin.logout')"
                method="post"
                as="button"
                class="rounded-lg border border-white px-4 py-3 text-left text-sm font-bold text-white transition hover:bg-white hover:text-black"
            >
                Logout
            </Link>
        </aside>

        <main class="min-h-screen lg:pl-72">
            <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                <div v-if="flash.success" class="mb-6 rounded-lg border border-black bg-white px-4 py-3 text-sm font-bold text-black">
                    {{ flash.success }}
                </div>
                <div v-if="flash.error" class="mb-6 rounded-lg border border-black bg-black px-4 py-3 text-sm font-bold text-white">
                    {{ flash.error }}
                </div>

                <slot />
            </div>
        </main>
    </div>
</template>
