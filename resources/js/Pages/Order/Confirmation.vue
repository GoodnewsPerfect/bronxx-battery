<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';
import { formatEspees } from '@/Support/pricing.js';

const props = defineProps({
    order: Object,
});

const page = usePage();
const pageTitle = computed(() => `Order Confirmation | ${page.props.name ?? 'Bronx'}`);
const order = ref({ ...props.order });
const confirmationStatus = ref('');
const returnParams = new URLSearchParams(window.location.search);
const returnedPaymentMessage = returnParams.get('message')
    || returnParams.get('status_details')
    || returnParams.get('error')
    || '';
const orderCode = (orderRecord) => `ORD-${String(orderRecord.id).padStart(10, '0')}`;

const formatDate = (value) => new Intl.DateTimeFormat('en-GB', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
}).format(new Date(value));

const isPaid = computed(() => order.value.payment_status === 'paid');

const statusClass = (status) => ({
    pending: 'bg-orange-50 text-orange-600',
    processing: 'bg-orange-50 text-orange-600',
    completed: 'bg-green-50 text-green-700',
    paid: 'bg-green-50 text-green-700',
    cancelled: 'bg-red-50 text-red-700',
    canceled: 'bg-red-50 text-red-700',
    failed: 'bg-red-50 text-red-700',
    unpaid: 'bg-red-50 text-red-700',
}[status] ?? 'bg-gray-100 text-gray-700');

const titleCase = (value) => String(value ?? '')
    .replace(/_/g, ' ')
    .replace(/\b\w/g, (letter) => letter.toUpperCase());

const orderItems = computed(() => order.value.items ?? []);

const orderSubtotal = computed(() => orderItems.value.reduce(
    (total, item) => total + Number(item.subtotal ?? (Number(item.price) * Number(item.quantity))),
    0,
));

onMounted(() => {
    if (returnedPaymentMessage) {
        confirmationStatus.value = returnedPaymentMessage;
    }

    if (!order.value.payment_id || order.value.payment_status === 'paid') {
        return;
    }

    confirmationStatus.value = confirmationStatus.value || 'Confirming Espees payment...';

    window.axios.post(route('payment.confirm-espees'), {
        order_id: String(order.value.id),
    }).then(({ data }) => {
        if (data.transaction_status === 'APPROVED') {
            order.value.payment_status = 'paid';
            confirmationStatus.value = data.status_details || 'Payment confirmed.';
            return;
        }

        confirmationStatus.value = data.status_details || `Payment status: ${data.transaction_status || 'pending'}`;
    }).catch((error) => {
        confirmationStatus.value = error.response?.data?.status_details
            || error.response?.data?.message
            || 'We could not confirm the payment yet. Please refresh this page shortly.';
    });
});
</script>

<template>
    <Head :title="pageTitle" />

    <StorefrontLayout :newsletter="false">
        <div class="flex-1 bg-[#F4F7FB] py-12 sm:py-16">
            <div class="mx-auto w-full max-w-2xl px-4 sm:px-6">
                <!-- Status banner -->
                <div
                    class="mb-6 flex items-center gap-4 rounded-xl p-5"
                    :class="isPaid ? 'bg-green-50' : 'bg-orange-50'"
                >
                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full"
                        :class="isPaid ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-600'"
                    >
                        <svg v-if="isPaid" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <svg v-else class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="font-bold" :class="isPaid ? 'text-green-800' : 'text-orange-800'">
                            {{ isPaid ? 'Payment successful' : 'Payment pending' }}
                        </p>
                        <p v-if="confirmationStatus" class="mt-0.5 text-sm text-gray-600">{{ confirmationStatus }}</p>
                    </div>
                </div>

                <div class="relative rounded-lg bg-white p-5 shadow-xl sm:p-8">
                    <Link
                        :href="route('orders.index')"
                        class="absolute right-4 top-4 flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-gray-500 shadow-sm hover:bg-gray-50 hover:text-gray-900"
                        aria-label="Close order details"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </Link>

                    <div class="pr-10">
                        <h2 class="text-xl font-bold text-gray-900 sm:text-2xl">Order Receipt</h2>
                        <p class="mt-2 text-sm text-gray-500 sm:text-base">Order #{{ orderCode(order) }}</p>
                    </div>

                    <div class="mt-6 grid gap-6 sm:grid-cols-2">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Order Number</p>
                            <p class="mt-1 font-bold text-gray-900">{{ orderCode(order) }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Order Date</p>
                            <p class="mt-1 font-bold text-gray-900">{{ formatDate(order.created_at) }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Status</p>
                            <span class="mt-2 inline-flex rounded-lg px-3 py-1.5 text-sm font-bold" :class="statusClass(order.status)">
                                {{ titleCase(order.status) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Payment Status</p>
                            <p class="mt-1 font-bold text-gray-900">{{ titleCase(order.payment_status) }}</p>
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
                                    class="h-16 w-16 shrink-0 rounded-lg object-cover sm:h-20 sm:w-20"
                                >

                                <div class="min-w-0 flex-1">
                                    <h4 class="truncate font-bold text-gray-900">{{ item.product_name }}</h4>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Quantity: {{ item.quantity }} × {{ formatEspees(item.price) }}
                                    </p>
                                </div>

                                <p class="font-bold text-gray-900">{{ formatEspees(item.subtotal) }}</p>
                            </div>

                            <div v-if="!orderItems.length" class="rounded-xl border border-dashed border-gray-200 p-6 text-center text-gray-500">
                                No items found for this order.
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 space-y-3 border-t border-gray-200 pt-6">
                        <div class="flex items-center justify-between text-sm text-gray-600">
                            <span>Subtotal</span>
                            <span class="text-gray-900">{{ formatEspees(orderSubtotal) }}</span>
                        </div>
                        <div class="flex items-center justify-between border-t border-gray-200 pt-4 text-xl font-bold text-gray-900">
                            <span>Total paid</span>
                            <span class="flex items-center gap-2">
                                <img src="/images/espees_logo.png" alt="Espees" class="h-5 w-5 object-contain">
                                {{ formatEspees(order.total_amount) }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h3 class="text-lg font-bold text-gray-900">Delivery Address</h3>
                        <p class="mt-3 text-sm text-gray-500">
                            {{ order.shipping_address || 'No delivery address provided.' }}
                        </p>
                    </div>

                    <div class="mt-8 border-t border-gray-200 pt-6">
                        <Link
                            :href="route('product.index')"
                            class="inline-flex w-full items-center justify-center rounded-lg bg-brand px-5 py-3 text-base font-semibold text-white shadow-sm transition hover:bg-brand-dark sm:w-auto"
                        >
                            Continue Shopping
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </StorefrontLayout>
</template>
