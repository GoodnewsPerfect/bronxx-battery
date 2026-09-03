<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    product: Object,
});

const imagePreview = ref(props.product?.image_url || '');
const isEditing = computed(() => Boolean(props.product));

const form = useForm({
    name: props.product?.name || '',
    description: props.product?.description || '',
    price: props.product?.price || '',
    image: null,
    is_sold_out: props.product?.is_sold_out || false,
});

const previewImage = (event) => {
    const file = event.target.files?.[0];
    form.image = file || null;
    imagePreview.value = file ? URL.createObjectURL(file) : (props.product?.image_url || '');
};

const submit = () => {
    if (isEditing.value) {
        form
            .transform((data) => ({
                ...data,
                _method: 'patch',
            }))
            .post(route('admin.products.update', props.product.id), {
                forceFormData: true,
                preserveScroll: true,
                onFinish: () => form.transform((data) => data),
            });
        return;
    }

    form.post(route('admin.products.store'), {
        forceFormData: true,
        preserveScroll: true,
    });
};
</script>

<template>
    <Head :title="isEditing ? 'Edit Product' : 'Add Product'" />

    <AdminLayout>
        <div>
            <h1 class="text-3xl font-black">{{ isEditing ? 'Edit Product' : 'Add Product' }}</h1>
            <p class="mt-1 text-gray-500">Create or update product details.</p>
        </div>

        <form class="mt-8 grid gap-8 lg:grid-cols-[1fr_320px]" @submit.prevent="submit">
            <section class="space-y-5 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <label class="block">
                    <span class="text-sm font-bold">Product name</span>
                    <input v-model="form.name" type="text" class="mt-2 h-12 w-full rounded-lg border border-gray-300 px-4 focus:border-brand focus:ring-brand">
                    <span v-if="form.errors.name" class="mt-2 block text-sm text-red-600">{{ form.errors.name }}</span>
                </label>

                <label class="block">
                    <span class="text-sm font-bold">Description</span>
                    <textarea v-model="form.description" rows="6" class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-brand focus:ring-brand" />
                    <span v-if="form.errors.description" class="mt-2 block text-sm text-red-600">{{ form.errors.description }}</span>
                </label>

                <label class="block">
                    <span class="text-sm font-bold">Price (Espees)</span>
                    <input v-model="form.price" type="number" min="0" step="0.01" class="mt-2 h-12 w-full rounded-lg border border-gray-300 px-4 focus:border-brand focus:ring-brand">
                    <span v-if="form.errors.price" class="mt-2 block text-sm text-red-600">{{ form.errors.price }}</span>
                </label>

                <label class="flex items-center gap-3 text-sm font-bold">
                    <input v-model="form.is_sold_out" type="checkbox" class="rounded border-gray-300 text-brand focus:ring-brand">
                    Mark as sold out
                </label>

                <div class="flex flex-wrap gap-3 pt-4">
                    <button type="submit" :disabled="form.processing" class="rounded-lg bg-brand px-5 py-3 text-sm font-bold text-white transition hover:bg-brand-dark disabled:opacity-50">
                        {{ isEditing ? 'Update Product' : 'Create Product' }}
                    </button>
                    <Link :href="route('admin.products.index')" class="rounded-lg border border-gray-300 px-5 py-3 text-sm font-bold hover:bg-gray-50">
                        Cancel
                    </Link>
                </div>
            </section>

            <aside class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-black">Product Image</h2>
                <div class="mt-4 aspect-square overflow-hidden rounded-lg border border-gray-200 bg-gray-100">
                    <img v-if="imagePreview" :src="imagePreview" alt="Product preview" class="h-full w-full object-cover">
                    <div v-else class="flex h-full items-center justify-center text-sm font-bold text-gray-500">Preview</div>
                </div>
                <input type="file" accept="image/*" class="mt-4 block w-full text-sm" @change="previewImage">
                <span v-if="form.errors.image" class="mt-2 block text-sm text-red-600">{{ form.errors.image }}</span>
            </aside>
        </form>
    </AdminLayout>
</template>
