<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, Link } from '@inertiajs/vue3';

const props = defineProps({
        user: Object,
        transactions: Object || null
    });

    const title = "Users transaction histories"

</script>

<template>
    <Head :title="title" />

    <AuthenticatedLayout>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="rounded-2xl flex-col bg-white flex mb-6 p-6">
                    <table class="table-auto">
                        <thead>
                        <th>Date</th>
                        <th>Ref</th>
                        <th>Paid In</th>
                        <th>Paid Out</th>
                        <th>Balance</th>
                        </thead>
                        <tbody>
                        <tr v-for="transaction in transactions.data" :key="transaction.id">
                            <td>{{ transaction.created_at }}</td>
                            <td>{{ transaction.ref }}</td>
                            <td>{{ !transaction.is_credit ? transaction.amount : "" }}</td>
                            <td>{{ transaction.is_credit ? transaction.amount : "" }}</td>
                            <td>{{ transaction.balance }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
