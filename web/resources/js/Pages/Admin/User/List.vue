<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, Link } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import TextInput from '@/Components/TextInput.vue';
import Pagination from "@/Components/Shared/Pagination.vue";

const props = defineProps({
        users: Object,
        searchValue: null
    });

    const title = "Users"

    const search = ref(props.searchValue);
    watch(search, async () => {
      router.get(route('admin.user.index'), {'search': search.value}, {replace: true, preserveState:true, preserveScroll: true});
    });
</script>

<template>
    <Head :title="title" />

    <AuthenticatedLayout>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 my-4 text-right">
                <Link
                    :href="route('admin.user.create')"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none justify-end"
                >Add User</Link>
            </div>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="rounded-2xl flex-col bg-white flex mb-6 p-6">
                    <div class="flex-1">
                        <div class="flex float-right">
                            <TextInput
                                id="search"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="search"
                            />
                        </div>
                    </div>
                    <table class="table-auto">
                        <thead>
                        <th>ID</th>
                        <th>Name</th>
                        <th>DOB</th>
                        <th>Address</th>
                        <th>Created At</th>
                        <th>Action</th>
                        </thead>
                        <tbody>
                        <tr v-for="user in users.data" :key="user.id">
                            <td>{{ user.id }}</td>
                            <td>{{ user.first_name }} {{ user.last_name }}</td>
                            <td>{{ user.dob }}</td>
                            <td>{{ user.post_code }}</td>
                            <td>{{ user.created_at }}</td>
                            <td>
                                <Link
                                    :href="route('admin.user.edit', user.id)"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-2 py-1 focus:outline-none"
                                >Edit</Link>
                                <Link
                                    :href="route('admin.user.transaction', user.id)"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-2 py-1 focus:outline-none ml-2"
                                >Transactions</Link>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <Pagination :links="users.meta.links" :meta="users.meta" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
