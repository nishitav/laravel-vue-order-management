<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head } from '@inertiajs/vue3';
    import PrimaryButton from '@/Components/PrimaryButton.vue';

    defineProps({
        mustVerifyEmail: {
            type: Boolean,
        },
        status: {
            type: String,
        },
    });
</script>

<template>
    <Head title="Order List" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Order List</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Order Number
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Customer Name
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Customer Email
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Customer Phone
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Payment Method
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(order, index) in orders" :key="order.id" :class="{ 'bg-gray-100': index % 2 === 1 }">
                                <td class="px-6 py-4 whitespace-nowrap">{{ order.order_number }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ order.customer.name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ order.customer.email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ order.customer.phone }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ order.total }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ order.payment_method }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ order.status }}</td>
                            </tr>
                        </tbody>
                    </table>


                    <div class="flex items-center justify-between mt-4">
                        <button 
                            @click="prevPage" 
                            :disabled="currentPage === 1"
                            class="px-4 py-2 bg-gray-800 text-white rounded-md shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 disabled:opacity-50"
                            >
                            Previous
                        </button>
                        <span class="text-gray-700">Page {{ currentPage }}</span>
                        <button 
                            @click="nextPage" 
                            :disabled="currentPage === totalPages"
                            class="px-4 py-2 bg-gray-800 text-white rounded-md shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 disabled:opacity-50"
                            >
                            Next
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script>
    import axios from 'axios';

    export default {
        data() {
            return {
                orders: [],
                currentPage: 1,
                totalPages: 1,
            };
        },
        mounted() {
            this.loadOrders();
        },
        methods: {
            loadOrders() {
                axios.get('/api/orders', {
                    params: {page: this.currentPage}
                })
                        .then(response => {
                            this.orders = response.data.data;
                            this.totalPages = response.data.last_page;
                        })
                        .catch(error => {
                            console.error('Error loading orders:', error);
                        });
            },
            prevPage() {
                if (this.currentPage > 1) {
                    this.currentPage--;
                    this.loadOrders();
                }
            },
            nextPage() {
                if (this.currentPage < this.totalPages) {
                    this.currentPage++;
                    this.loadOrders();
                }
            },
        },
    };
</script>