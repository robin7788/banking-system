<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    first_name: '',
    last_name: '',
    dob: '',
    address_1: '',
    address_2: '',
    town: '',
    country: '',
    post_code: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="first_name" value="First Name" />

                <TextInput
                    id="first_name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.first_name"
                    required
                    autofocus
                    autocomplete="first_name"
                />

                <InputError class="mt-2" :message="form.errors.first_name" />
            </div>

            <div class="mt-4">
                <InputLabel for="last_name" value="Last Name" />

                <TextInput
                    id="last_name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.last_name"
                    required
                />

                <InputError class="mt-2" :message="form.errors.last_name" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="dob" value="Date of Birth" />

                <TextInput
                    id="dob"
                    type="date"
                    class="mt-1 block w-full"
                    v-model="form.dob"
                    required
                />

                <InputError class="mt-2" :message="form.errors.dob" />
            </div>

            <div class="mt-4">
                <InputLabel for="address_1" value="Address 1" />

                <TextInput
                    id="address_1"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.address_1"
                    required
                />

                <InputError class="mt-2" :message="form.errors.address_1" />
            </div>

            <div class="mt-4">
                <InputLabel for="address_2" value="Address 2" />

                <TextInput
                    id="address_2"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.address_2"
                />

                <InputError class="mt-2" :message="form.errors.address_2" />
            </div>

            <div class="mt-4">
                <InputLabel for="town" value="Town" />

                <TextInput
                    id="town"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.town"
                    required
                />

                <InputError class="mt-2" :message="form.errors.town" />
            </div>


            <div class="mt-4">
                <InputLabel for="country" value="Country" />

                <TextInput
                    id="country"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.country"
                    required
                />

                <InputError class="mt-2" :message="form.errors.country" />
            </div>

            <div class="mt-4">
                <InputLabel for="post_code" value="Postcode" />

                <TextInput
                    id="post_code"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.post_code"
                    required
                />

                <InputError class="mt-2" :message="form.errors.post_code" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel for="password_confirmation" value="Confirm Password" />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link
                    :href="route('login')"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Already registered?
                </Link>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Register
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
