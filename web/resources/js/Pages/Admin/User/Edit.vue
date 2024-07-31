<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, Link } from '@inertiajs/vue3';
import UserForm from './Partials/UserForm.vue';

const props = defineProps({
        user: {
          type: Object
        }
    });

const title = "Edit User: " + props.user?.name

</script>

<template>
    <Head :title="title" />

    <AuthenticatedLayout>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="rounded-2xl flex-col bg-white flex mb-6 p-6">
                    <UserForm :user="user"></UserForm>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
