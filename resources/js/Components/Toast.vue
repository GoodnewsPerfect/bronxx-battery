<script setup>
import { ref } from 'vue';

const notifications = ref([]);

const add = (message, type = 'success') => {
    const id = Date.now();
    notifications.value.push({ id, message, type });
    setTimeout(() => {
        remove(id);
    }, 5000);
};

const remove = (id) => {
    notifications.value = notifications.value.filter(n => n.id !== id);
};

defineExpose({ add });
</script>

<template>
    <div class="fixed left-4 right-4 top-4 z-[9999] flex flex-col items-stretch gap-2 pointer-events-none sm:left-auto sm:right-6 sm:top-6 sm:w-[420px]">
        <TransitionGroup
            tag="div"
            enter-active-class="transform ease-out duration-300 transition"
            enter-from-class="translate-y-2 opacity-0"
            enter-to-class="translate-y-0 opacity-100"
            leave-active-class="transition ease-in duration-100"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div 
                v-for="notification in notifications" 
                :key="notification.id"
                class="w-full rounded-xl border border-gray-100 bg-white shadow-xl pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden"
            >
                <div class="p-3 sm:p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg v-if="notification.type === 'success'" class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <svg v-else-if="notification.type === 'info'" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z" />
                            </svg>
                            <svg v-else class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-2 min-w-0 flex-1">
                            <p class="text-xs font-bold text-gray-900">
                                {{ notification.type === 'success' ? 'Success' : notification.type === 'info' ? 'Info' : 'Error' }}
                            </p>
                            <p class="mt-0.5 text-xs leading-snug text-gray-500 sm:text-sm">
                                {{ notification.message }}
                            </p>
                        </div>
                        <div class="ml-3 flex-shrink-0 flex">
                            <button type="button" @click="remove(notification.id)" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none">
                                <span class="sr-only">Close</span>
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </TransitionGroup>
    </div>
</template>
