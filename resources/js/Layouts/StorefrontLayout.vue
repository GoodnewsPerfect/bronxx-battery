<script setup>
import { router, useForm, usePage } from '@inertiajs/vue3';
import { computed, onMounted, provide, ref, watch } from 'vue';
import Toast from '@/Components/Toast.vue';
import Sidebar from '@/Components/Sidebar.vue';
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';

defineProps({
    newsletter: {
        type: Boolean,
        default: true,
    },
});

defineEmits(['toggleSearch']);

const page = usePage();
const isSidebarOpen = ref(false);
const toast = ref(null);
provide('toast', toast);

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

const showFlash = () => {
    if (page.props.flash?.success) {
        toast.value?.add(page.props.flash.success, 'success');
    }

    if (page.props.flash?.error) {
        toast.value?.add(page.props.flash.error, 'error');
    }
};

onMounted(showFlash);
watch(() => page.props.flash, showFlash);

const user = computed(() => page.props.auth.user);

const newsletterForm = useForm({
    email: user.value?.email ?? '',
});

const submitNewsletter = () => {
    if (!user.value) {
        router.visit(route('login'));
        return;
    }

    newsletterForm.post(route('newsletter.subscribe'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <div class="flex min-h-screen flex-col overflow-x-hidden bg-white pt-28 font-sans text-gray-900">
        <Toast ref="toast" />
        <Sidebar :open="isSidebarOpen" />
        <Header @toggle-sidebar="toggleSidebar" @toggle-search="$emit('toggleSearch')" />

        <main class="flex flex-1 flex-col">
            <slot />
        </main>

        <section v-if="newsletter" class="mt-auto bg-cyan py-16 text-white">
            <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-8 px-4 text-center sm:px-6 md:flex-row md:text-left lg:px-8">
                <div class="w-full md:w-auto">
                    <h2 class="mb-2 text-3xl font-bold">Subscribe Newsletter</h2>
                    <p class="text-white/90">Stay up to date with the latest news and offers</p>
                </div>
                <form class="flex w-full max-w-md flex-col gap-1 rounded-md bg-white p-1 shadow-lg sm:flex-row sm:gap-0" @submit.prevent="submitNewsletter">
                    <label for="newsletter-email" class="sr-only">Email address</label>
                    <input
                        id="newsletter-email"
                        v-model="newsletterForm.email"
                        type="email"
                        required
                        placeholder="Your email address"
                        class="min-w-0 flex-1 border-none px-4 py-3 text-gray-900 placeholder-gray-400 focus:ring-0 sm:py-2"
                    >
                    <button
                        type="submit"
                        :disabled="newsletterForm.processing"
                        class="rounded bg-gold px-6 py-3 font-bold text-black transition hover:bg-gold-dark disabled:cursor-not-allowed disabled:opacity-70 sm:py-2"
                    >
                        Subscribe
                    </button>
                </form>
            </div>
        </section>

        <Footer />
    </div>
</template>
