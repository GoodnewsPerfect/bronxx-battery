<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps({
    open: Boolean,
});

const page = usePage();
const currentPath = computed(() => page.url.split('?')[0]);

const isActivePath = (paths) => {
    const activePaths = Array.isArray(paths) ? paths : [paths];

    return activePaths.some((path) => {
        if (path === '/') {
            return currentPath.value === '/';
        }

        return currentPath.value === path || currentPath.value.startsWith(`${path}/`);
    });
};

const navItemClass = (paths) => [
    'flex items-center rounded-md px-4 py-2 transition',
    isActivePath(paths)
        ? 'bg-[#0056D2] text-white'
        : 'text-gray-800 hover:bg-gray-100',
];
</script>

<template>
    <aside
        class="fixed inset-y-0 left-0 z-[55] w-64 transform bg-white shadow-2xl transition-transform duration-300"
        :class="{ '-translate-x-full': !open }"
    >
        <div class="flex flex-col h-full">
            <div class="flex h-20 items-center justify-center border-b border-gray-100 bg-[#0056D2]">
                <Link href="/" class="flex items-center">
                    <img src="/images/Bronx_Logo_2-removebg-preview.png" alt="Bronx Logo" class="h-10 w-auto">
                </Link>
            </div>
            <nav class="flex-1 space-y-2 overflow-y-auto px-4 py-6">
                <Link
                    href="/"
                    :class="navItemClass('/')"
                >
                    <span>Home</span>
                </Link>
                <Link
                    :href="route('product.index')"
                    :class="navItemClass('/product')"
                >
                    <span>Product</span>
                </Link>
                <Link
                    :href="route('about.index')"
                    :class="navItemClass('/about')"
                >
                    <span>About</span>
                </Link>
                <Link
                    :href="route('contact.index')"
                    :class="navItemClass('/contact')"
                >
                    <span>Contact</span>
                </Link>
                <Link
                    :href="$page.props.auth.user ? route('orders.index') : route('login')"
                    :class="navItemClass(['/orders', '/dashboard'])"
                >
                    <span>My Account</span>
                </Link>
            </nav>
            <div class="border-t border-gray-100 p-4">
                <div v-if="!$page.props.auth.user" class="space-y-2">
                    <Link
                        :href="route('login')"
                        class="block w-full rounded-md bg-[#0056D2] px-4 py-2 text-center text-white transition hover:bg-[#0047AB]"
                    >
                        Login
                    </Link>
                    <Link
                        :href="route('register')"
                        class="block w-full rounded-md border border-gray-200 px-4 py-2 text-center text-gray-800 transition hover:bg-gray-50"
                    >
                        Register
                    </Link>
                </div>
                <div v-else class="space-y-2">
                    <div class="px-4 text-sm text-gray-500">
                        {{ $page.props.auth.user.name }}
                    </div>
                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="block w-full rounded-md border border-gray-200 px-4 py-2 text-center text-gray-800 transition hover:bg-gray-50"
                    >
                        Logout
                    </Link>
                </div>
            </div>
        </div>
    </aside>
</template>
