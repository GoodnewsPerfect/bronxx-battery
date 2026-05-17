<script setup>
import { onMounted } from 'vue';
import { router, Head } from '@inertiajs/vue3';
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';

defineProps({
    kingschat_auth_url: String
});

onMounted(() => {
    // Try to extract access_token from URL fragment (#access_token=...)
    let hash = window.location.hash.substring(1);
    
    // If no hash, try query string as fallback
    if (!hash) {
        const urlParams = new URLSearchParams(window.location.search);
        const token = urlParams.get('access_token');
        if (token) {
            hash = `access_token=${token}`;
        }
    }

    const params = new URLSearchParams(hash);
    const accessToken = params.get('accessToken') || params.get('access_token');

    if (accessToken) {
        // Send to backend via POST using camelCase (KingsChat standard)
        router.post('/auth/kingschat/callback', {
            accessToken: accessToken
        }, {
            onError: (errors) => {
                console.error('KingsChat Auth Error:', errors);
                router.visit('/login');
            }
        });
    } else {
        // No token found, redirect to login after a short delay
        setTimeout(() => {
            router.visit('/login');
        }, 2000);
    }
});
</script>

<template>
    <Head title="Authenticating..." />

    <div class="min-h-screen bg-white text-gray-900 font-sans flex flex-col pt-28">
        <Header />

        <main class="flex-1 flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-white">
            <div class="w-full max-w-md space-y-8 bg-white p-8 rounded-2xl shadow-sm border border-gray-100 text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#0056D2] mx-auto mb-4"></div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Authenticating</h2>
                <p class="text-gray-600 font-medium">Please wait while we complete your KingsChat login...</p>
            </div>
        </main>

        <Footer />
    </div>
</template>
