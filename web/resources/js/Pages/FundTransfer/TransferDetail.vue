<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    amount: {
          type: Number
    },
    account_number: Number,
    transfer_amount: Number,
    charge: Number,
    receiptant: Object,
    rate: Number,
});

const form = useForm({
    amount: props.amount,
    account_number: props.account_number
});


const submit = () => {
    form.post(route('user.fund.transfer.confirm'));
};

</script>

<template>
    <AuthenticatedLayout>
        <Head title="Fund Transfer" />
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                    <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                        {{ status }}
                    </div>

                    <header class="border-b-2 mb-2">
                        <h2 class="text-lg font-medium text-gray-900">Sender info:</h2>
                    </header>
                    <p>Account : <span class="font-bold">{{ $page.props.auth.user.account?.account_number }}</span></p>
                    <p class="mb-5">Name : <span class="font-bold">{{ $page.props.auth.user.name }}</span></p>

                    <header class="border-b-2 mb-2">
                        <h2 class="text-lg font-medium text-gray-900">Recipient info:</h2>
                    </header>
                    <p>Account : <span class="font-bold">{{ account_number }}</span></p>
                    <p class="mb-5">Name : <span class="font-bold">{{ receiptant?.user?.name }}</span></p>

                    <header class="border-b-2 mb-2">
                        <h2 class="text-lg font-medium text-gray-900">Transfer detail:</h2>
                    </header>
                    <p>Amount: 
                        <span class="font-bold">
                            {{ parseFloat(amount).toFixed(2) }} {{ $page.props.auth.user.account?.currency.toUpperCase() }}
                        </span>
                    </p>
                    <p v-if="rate > 0">Exchange Rate: 
                        <span class="font-bold">
                            {{ parseFloat(rate).toFixed(2) }}
                        </span>
                    </p>

                    <p v-if="charge > 0">Charge: 
                        <span class="font-bold">
                            {{ parseFloat(charge).toFixed(2) }}
                        </span>
                    </p>

                    <p>They Receive: 
                        <span class="font-bold">
                            {{ parseFloat(transfer_amount).toFixed(2) }} {{ receiptant?.currency.toUpperCase() }}
                        </span>
                    </p>

                    <form @submit.prevent="submit">
                        <TextInput
                            id="account_number"
                            type="hidden"
                            v-model="form.account_number"
                        />
                        <TextInput
                            id="amount"
                            type="hidden"
                            v-model="form.amount"
                        />

                        <div class="flex items-center justify-end mt-4">
                            <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Send
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
