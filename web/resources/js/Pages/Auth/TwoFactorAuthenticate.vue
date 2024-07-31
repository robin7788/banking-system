<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
    code: {
        type: String,
    },
});

const form = useForm({
    two_factor_code: props.code,
});


const submit = () => {
    form.post(route('verify.store'));
};

</script>

<template>
    <GuestLayout>
        <Head title="Two Factor Authentication" />

        <div class="mb-4 text-sm text-gray-600">
            Please enter the 2FA code that has been sent to your email.
        </div>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="two_factor_code" value="Two Factor Code" />

                <TextInput
                    id="two_factor_code"
                    type="number"
                    class="mt-1 block w-full"
                    v-model="form.two_factor_code"
                    required
                    autofocus
                />

                <InputError class="mt-2" :message="form.errors.two_factor_code" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link
                    :href="route('verify.resend')"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Resend code
                </Link>
                
                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Verify
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
