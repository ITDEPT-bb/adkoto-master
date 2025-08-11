<template>
    <div class="bg-white p-4 rounded">
        <h3 class="font-semibold mb-2">Auction Items</h3>
        <ul class="space-y-2 max-h-60 overflow-auto">
            <li
                v-for="(item, index) in items"
                :key="item.id"
                class="p-2 rounded flex justify-between items-start"
                :class="{ 'bg-blue-50': index === currentIndex }"
                @click="$emit('select-index', index)"
            >
                <div>
                    <div class="font-semibold">{{ item.name }}</div>
                    <div class="text-xs text-gray-500">
                        {{ item.description }}
                    </div>
                </div>
                <div class="text-right">
                    <div class="font-semibold">
                        ₱{{ item.current_price ?? item.starting_price }}
                    </div>
                    <div class="text-xs mt-1">
                        <button
                            class="text-xs mr-1"
                            @click.stop="$emit('set-active', item.id)"
                        >
                            Set Active
                        </button>
                        <button
                            class="text-xs"
                            @click.stop="$emit('set-next', item.id)"
                        >
                            Set Next
                        </button>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</template>

<script setup>
const props = defineProps({ items: Array, currentIndex: Number });
</script>
