<script setup>
const props = defineProps({
    modelValue: {
        type: Number,
        required: true,
    },
    min: {
        type: Number,
        default: 1,
    },
    label: {
        type: String,
        default: 'Quantity',
    },
    id: {
        type: String,
        default: 'quantity',
    },
});

const emit = defineEmits(['update:modelValue']);

const clamp = (value) => Math.max(props.min, Number(value) || props.min);

const decrement = () => emit('update:modelValue', clamp(props.modelValue - 1));
const increment = () => emit('update:modelValue', clamp(props.modelValue + 1));
const onInput = (event) => emit('update:modelValue', clamp(event.target.value));
</script>

<template>
    <div>
        <label :for="id" class="mb-3 block text-sm font-semibold text-gray-700">{{ label }}</label>
        <div class="inline-flex items-center gap-2">
            <button
                type="button"
                @click="decrement"
                :disabled="modelValue <= min"
                class="flex h-11 w-11 items-center justify-center rounded-lg border border-gray-200 bg-white text-xl leading-none text-gray-900 shadow-sm transition hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-40"
                aria-label="Decrease quantity"
            >&minus;</button>
            <input
                :id="id"
                type="number"
                :min="min"
                :value="modelValue"
                @input="onInput"
                class="h-11 w-16 rounded-lg border border-gray-200 text-center text-base text-gray-900 focus:border-brand focus:ring-brand"
            >
            <button
                type="button"
                @click="increment"
                class="flex h-11 w-11 items-center justify-center rounded-lg border border-gray-200 bg-white text-xl leading-none text-gray-900 shadow-sm transition hover:bg-gray-50"
                aria-label="Increase quantity"
            >&plus;</button>
        </div>
    </div>
</template>
