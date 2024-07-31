<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

const props = defineProps({
    user: {
        type: Object || null,
    }
});

const user = usePage().props.auth.user;

const form = useForm({
    id: props.user ? props.user.id : null,
    email: props.user ? props.user.email : null,
    first_name: props.user ? props.user.first_name : null,
    last_name: props.user ? props.user.last_name : null,
    dob: props.user ? props.user.dob : null,
    address_1: props.user ? props.user.address_1 : null,
    address_2: props.user ? props.user.address_2 : null,
    town: props.user ? props.user.town : null,
    country: props.user ? props.user.country : null,
    post_code: props.user ? props.user.post_code : null,
    password: '',
});

function handleSubmit () {
    console.log()
    if(form.id) {
        form.patch(route('admin.user.update', form.id));
    } else {
        form.post(route('admin.user.store'));
    }
}

</script>

<template>
    <section>
        <form @submit.prevent="handleSubmit" class="mt-6 space-y-6">
            <div>
                <InputLabel for="first_name" value="First Name" />

                <TextInput
                    id="first_name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.first_name"
                    required
                    autofocus
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
                />

                <InputError class="mt-2" :message="form.errors.last_name" />
            </div>

            <div class="mt-4">
                <InputLabel for="dob" value="Date of Birth" />

                <TextInput
                    id="dob"
                    type="date"
                    class="mt-1 block w-full"
                    v-model="form.dob"
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
                />

                <InputError class="mt-2" :message="form.errors.post_code" />
            </div>

            <div>
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
            <div>
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    ref="password"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full"
                />

                <InputError :message="form.errors.password" class="mt-2" />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
