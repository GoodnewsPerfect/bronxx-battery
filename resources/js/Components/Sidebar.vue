<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    open: Boolean,
});
</script>

<template>
    <aside
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transition-transform duration-300 transform dark:bg-zinc-900"
        :class="{ '-translate-x-full': !open }"
    >
        <div class="flex flex-col h-full">
            <div class="flex items-center justify-center h-16 border-b dark:border-zinc-800">
                <Link href="/" class="text-2xl font-bold text-black dark:text-white">
                    Bronx
                </Link>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <Link
                    href="/"
                    class="flex items-center px-4 py-2 text-gray-700 rounded-md hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-zinc-800"
                >
                    <span class="ml-3">Home</span>
                </Link>
                <!-- Add more links as needed -->
            </nav>
            <div class="p-4 border-t dark:border-zinc-800">
                <div v-if="!$page.props.auth.user" class="space-y-2">
                    <Link
                        :href="route('login')"
                        class="block w-full px-4 py-2 text-center text-white bg-blue-600 rounded-md hover:bg-blue-700"
                    >
                        Login
                    </Link>
                </div>
            </div>
        </div>
    </aside>
</template>
