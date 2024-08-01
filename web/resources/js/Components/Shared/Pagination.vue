<template>
    <div>
        <p class="mt-2">Showing from {{ meta.from }} to {{ meta.to }} out of {{ meta.total }}</p>
        <div v-if="links.length > 3">
            <div class="flex flex-wrap mt-8">
                <template v-for="(link, key) in links" :key="key">
                    <div
                        v-if="link.url === null"
                        class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded"
                        v-html="link.label"
                    />

                    <Link
                        v-else
                        class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded"
                        :class="{ 'bg-white text-black': link.active }"
                        :href="link.url"
                        v-html="link.label"
                        preserve-scroll
                    />
                </template>
            </div>
        </div>
    </div>
    
</template>

<script setup>
    import { Link } from "@inertiajs/vue3";
    const props = defineProps({
        links: {
            type: Object,
            required: true,
        },
        meta: {
            type: Object,
            required: true
        }
    });
    
</script>