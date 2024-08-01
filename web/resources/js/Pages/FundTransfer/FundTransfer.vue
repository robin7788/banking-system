<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    account_number: null,
    amount: 0,
});


const submit = () => {
    form.post(route('user.fund.transfer.detail'));
};

</script>

<template>
    <AuthenticatedLayout>
        <Head title="Fund Transfer" />
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                    <div class="mb-4 text-sm text-gray-600">
                        Please enter the recepient account number.
                    </div>

                    <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                        {{ status }}
                    </div>

                    <form @submit.prevent="submit">
                        <div>
                            <InputLabel for="account_number" value="Account Number" />

                            <TextInput
                                id="account_number"
                                type="number"
                                class="mt-1 block w-full"
                                v-model="form.account_number"
                                required
                                autofocus
                            />

                            <InputError class="mt-2" :message="form.errors.account_number" />
                        </div>

                        <div>
                            <InputLabel for="amount" value="Amount that you want to transfer?" />

                            <TextInput
                                id="amount"
                                type="number"
                                class="mt-1 block w-full"
                                step="0.01"
                                min="0.01"
                                v-model="form.amount"
                                required
                            />

                            <InputError class="mt-2" :message="form.errors.amount" />
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Submit
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
