<template>
    <div class="min-h-screen bg-gradient-to-br from-yellow-50 to-yellow-100">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b border-yellow-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <h1 class="text-3xl font-bold text-gray-900">Invoice Management</h1>
                    <Link :href="route('invoices.create')"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                    Create Invoice
                    </Link>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-sm border border-yellow-200 p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select v-model="filters.status" @change="applyFilters"
                            class="w-full border border-gray-300 rounded-md px-3 py-2">
                            <option value="">All Statuses</option>
                            <option value="draft">Draft</option>
                            <option value="sent">Sent</option>
                            <option value="paid">Paid</option>
                            <option value="overdue">Overdue</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Account</label>
                        <select v-model="filters.account_id" @change="applyFilters"
                            class="w-full border border-gray-300 rounded-md px-3 py-2">
                            <option value="">All Accounts</option>
                            <option v-for="account in accounts" :key="account.id" :value="account.id">
                                {{ account.name }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                        <input v-model="filters.date_from" @change="applyFilters" type="date"
                            class="w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                        <input v-model="filters.date_to" @change="applyFilters" type="date"
                            class="w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-sm border border-yellow-200 p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-yellow-100 rounded-lg">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Invoices</p>
                            <p class="text-2xl font-bold text-gray-900">{{ stats.total }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-yellow-200 p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Value</p>
                            <p class="text-2xl font-bold text-gray-900">£{{ stats.total_value?.toLocaleString() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-yellow-200 p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-red-100 rounded-lg">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Overdue</p>
                            <p class="text-2xl font-bold text-gray-900">{{ stats.overdue }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-yellow-200 p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Paid</p>
                            <p class="text-2xl font-bold text-gray-900">{{ stats.paid }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Invoices Table -->
            <div class="bg-white rounded-lg shadow-sm border border-yellow-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Invoices</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Invoice ID</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Customer</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Order</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Amount</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Due Date</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="invoice in invoices.data" :key="invoice.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #{{ invoice.equinox_id || invoice.id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ invoice.account?.name || 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ invoice.account?.code || '' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <Link :href="route('orders.show', invoice.order_id)"
                                        class="text-blue-600 hover:text-blue-900" v-if="invoice.order_id">
                                    #{{ invoice.order?.equinox_id || invoice.order_id }}
                                    </Link>
                                    <span v-else class="text-gray-400">-</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="getStatusClass(invoice.status)"
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                                        {{ invoice.status || 'draft' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    £{{ invoice.invoice_total_gross?.toLocaleString() ||
                                        invoice.amount?.toLocaleString() || '0.00' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ formatDate(invoice.due_date) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <Link :href="route('invoices.show', invoice.id)"
                                        class="text-yellow-600 hover:text-yellow-900">
                                    View
                                    </Link>
                                    <button @click="downloadInvoice(invoice)" class="text-blue-600 hover:text-blue-900">
                                        Download
                                    </button>
                                    <button @click="sendInvoice(invoice)" class="text-green-600 hover:text-green-900"
                                        v-if="invoice.status !== 'sent'">
                                        Send
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="invoices.links" class="px-6 py-3 border-t border-gray-200">
                    <nav class="flex justify-between">
                        <div class="flex space-x-2">
                            <Link v-for="link in invoices.links" :key="link.label" :href="link.url" :class="[
                                'px-3 py-2 text-sm rounded-md',
                                link.active ? 'bg-yellow-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'
                            ]" v-html="link.label">
                            </Link>
                        </div>
                    </nav>
                </div>
            </div>

            <!-- Aging Report -->
            <div class="mt-8 bg-white rounded-lg shadow-sm border border-yellow-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Aging Report</h3>
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">£{{ aging.current?.toLocaleString() || '0' }}
                        </div>
                        <div class="text-sm text-gray-600">Current</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-yellow-600">£{{ aging.days_30?.toLocaleString() || '0' }}
                        </div>
                        <div class="text-sm text-gray-600">1-30 Days</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-orange-600">£{{ aging.days_60?.toLocaleString() || '0' }}
                        </div>
                        <div class="text-sm text-gray-600">31-60 Days</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-red-600">£{{ aging.days_90?.toLocaleString() || '0' }}</div>
                        <div class="text-sm text-gray-600">61-90 Days</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-red-800">£{{ aging.over_90?.toLocaleString() || '0' }}</div>
                        <div class="text-sm text-gray-600">90+ Days</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { Link, router } from '@inertiajs/vue3'

const props = defineProps({
    invoices: Object,
    accounts: Array,
    filters: Object,
    stats: Object,
    aging: Object
})

const filters = reactive({
    status: props.filters?.status || '',
    account_id: props.filters?.account_id || '',
    date_from: props.filters?.date_from || '',
    date_to: props.filters?.date_to || ''
})

const getStatusClass = (status) => {
    const classes = {
        draft: 'bg-gray-100 text-gray-800',
        sent: 'bg-blue-100 text-blue-800',
        paid: 'bg-green-100 text-green-800',
        overdue: 'bg-red-100 text-red-800',
        cancelled: 'bg-red-100 text-red-800'
    }
    return classes[status] || 'bg-gray-100 text-gray-800'
}

const formatDate = (dateString) => {
    if (!dateString) return '-'
    return new Date(dateString).toLocaleDateString('en-GB', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    })
}

const applyFilters = () => {
    router.get(route('invoices.index'), filters, { preserveState: true })
}

const downloadInvoice = async (invoice) => {
    try {
        const response = await fetch(`/api/invoices/${invoice.id}/download`, {
            headers: {
                'Authorization': `Bearer ${window.apiToken}`,
            }
        })

        if (response.ok) {
            const blob = await response.blob()
            const url = window.URL.createObjectURL(blob)
            const a = document.createElement('a')
            a.style.display = 'none'
            a.href = url
            a.download = `invoice-${invoice.equinox_id || invoice.id}.pdf`
            document.body.appendChild(a)
            a.click()
            window.URL.revokeObjectURL(url)
        }
    } catch (error) {
        console.error('Download error:', error)
    }
}

const sendInvoice = async (invoice) => {
    try {
        const response = await fetch(`/api/invoices/${invoice.id}/send`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${window.apiToken}`,
                'Content-Type': 'application/json'
            }
        })

        if (response.ok) {
            router.reload()
        }
    } catch (error) {
        console.error('Send error:', error)
    }
}
</script>
