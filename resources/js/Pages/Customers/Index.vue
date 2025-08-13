<template>
    <div class="min-h-screen bg-gradient-to-br from-yellow-50 to-yellow-100">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b border-yellow-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <h1 class="text-3xl font-bold text-gray-900">Customer Accounts</h1>
                    <div class="flex space-x-3">
                        <button @click="syncAllAccounts"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium">
                            Sync with Equinox
                        </button>
                        <Link :href="route('customers.create')"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                        Add Customer
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Search and Filters -->
            <div class="bg-white rounded-lg shadow-sm border border-yellow-200 p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <input v-model="filters.search" @input="debounceSearch" placeholder="Search accounts..."
                            class="w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select v-model="filters.status" @change="applyFilters"
                            class="w-full border border-gray-300 rounded-md px-3 py-2">
                            <option value="">All Statuses</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="credit_hold">Credit Hold</option>
                            <option value="cash_only">Cash Only</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Credit Level</label>
                        <select v-model="filters.credit_level" @change="applyFilters"
                            class="w-full border border-gray-300 rounded-md px-3 py-2">
                            <option value="">All Levels</option>
                            <option value="low">Low Risk</option>
                            <option value="medium">Medium Risk</option>
                            <option value="high">High Risk</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                        <select v-model="filters.sort" @change="applyFilters"
                            class="w-full border border-gray-300 rounded-md px-3 py-2">
                            <option value="name">Name</option>
                            <option value="balance">Balance</option>
                            <option value="credit_limit">Credit Limit</option>
                            <option value="last_order">Last Order</option>
                        </select>
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
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Accounts</p>
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
                            <p class="text-sm font-medium text-gray-600">Total Credit</p>
                            <p class="text-2xl font-bold text-gray-900">£{{ stats.total_credit?.toLocaleString() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-yellow-200 p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Outstanding</p>
                            <p class="text-2xl font-bold text-gray-900">£{{ stats.outstanding?.toLocaleString() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-yellow-200 p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-purple-100 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Active This Month</p>
                            <p class="text-2xl font-bold text-gray-900">{{ stats.active_this_month }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Accounts Table -->
            <div class="bg-white rounded-lg shadow-sm border border-yellow-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Customer Accounts</h3>
                    <button @click="analyzeAllCustomers"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        AI Analysis
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Account</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Customer</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Balance</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Credit Limit</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Last Order</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="account in accounts.data" :key="account.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ account.name }}</div>
                                    <div class="text-sm text-gray-500">{{ account.code }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ account.customer?.name || 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ account.customer?.email || '' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="getStatusClass(account.account_status)"
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                                        {{ account.account_status || 'active' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span :class="account.balance > 0 ? 'text-red-600' : 'text-green-600'">
                                        £{{ account.balance?.toLocaleString() || '0.00' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    £{{ account.credit_limit?.toLocaleString() || '0.00' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ formatDate(account.last_order_date) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <Link :href="route('accounts.show', account.id)"
                                        class="text-yellow-600 hover:text-yellow-900">
                                    View
                                    </Link>
                                    <Link :href="route('orders.index', { account_id: account.id })"
                                        class="text-blue-600 hover:text-blue-900">
                                    Orders
                                    </Link>
                                    <button @click="analyzeCustomer(account)"
                                        class="text-purple-600 hover:text-purple-900">
                                        AI Analyze
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="accounts.links" class="px-6 py-3 border-t border-gray-200">
                    <nav class="flex justify-between">
                        <div class="flex space-x-2">
                            <Link v-for="link in accounts.links" :key="link.label" :href="link.url" :class="[
                                'px-3 py-2 text-sm rounded-md',
                                link.active ? 'bg-yellow-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'
                            ]" v-html="link.label">
                            </Link>
                        </div>
                    </nav>
                </div>
            </div>

            <!-- Credit Risk Analysis -->
            <div class="mt-8 bg-white rounded-lg shadow-sm border border-yellow-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Credit Risk Analysis</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600">{{ riskAnalysis.low_risk || 0 }}</div>
                        <div class="text-sm text-gray-600">Low Risk Accounts</div>
                        <div class="text-xs text-gray-500">0-30% credit utilization</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-yellow-600">{{ riskAnalysis.medium_risk || 0 }}</div>
                        <div class="text-sm text-gray-600">Medium Risk Accounts</div>
                        <div class="text-xs text-gray-500">30-70% credit utilization</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-red-600">{{ riskAnalysis.high_risk || 0 }}</div>
                        <div class="text-sm text-gray-600">High Risk Accounts</div>
                        <div class="text-xs text-gray-500">70%+ credit utilization</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- AI Analysis Modal -->
        <div v-if="showAnalysisModal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Customer Analysis</h3>
                        <button @click="showAnalysisModal = false" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div v-if="analysisLoading" class="text-center py-8">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-yellow-500 mx-auto"></div>
                        <p class="mt-4 text-gray-600">Analyzing customer data...</p>
                    </div>
                    <div v-else-if="analysisResult" class="space-y-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-2">AI Analysis Results</h4>
                            <pre class="text-sm text-gray-700 whitespace-pre-wrap">{{ analysisResult }}</pre>
                        </div>
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
    accounts: Object,
    filters: Object,
    stats: Object,
    riskAnalysis: Object
})

const showAnalysisModal = ref(false)
const analysisLoading = ref(false)
const analysisResult = ref(null)
let searchTimeout = null

const filters = reactive({
    search: props.filters?.search || '',
    status: props.filters?.status || '',
    credit_level: props.filters?.credit_level || '',
    sort: props.filters?.sort || 'name'
})

const getStatusClass = (status) => {
    const classes = {
        active: 'bg-green-100 text-green-800',
        inactive: 'bg-gray-100 text-gray-800',
        credit_hold: 'bg-red-100 text-red-800',
        cash_only: 'bg-yellow-100 text-yellow-800'
    }
    return classes[status] || 'bg-green-100 text-green-800'
}

const formatDate = (dateString) => {
    if (!dateString) return 'Never'
    return new Date(dateString).toLocaleDateString('en-GB', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    })
}

const debounceSearch = () => {
    clearTimeout(searchTimeout)
    searchTimeout = setTimeout(() => {
        applyFilters()
    }, 500)
}

const applyFilters = () => {
    router.get(route('accounts.index'), filters, { preserveState: true })
}

const syncAllAccounts = async () => {
    try {
        await fetch('/api/sync/accounts', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${window.apiToken}`,
                'Content-Type': 'application/json'
            }
        })
        router.reload()
    } catch (error) {
        console.error('Sync error:', error)
    }
}

const analyzeCustomer = async (account) => {
    if (!account.customer?.id) return

    showAnalysisModal.value = true
    analysisLoading.value = true
    analysisResult.value = null

    try {
        const response = await fetch(`/api/ai/customers/${account.customer.id}/analyze`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${window.apiToken}`,
            }
        })

        const data = await response.json()
        analysisResult.value = JSON.stringify(data.analysis, null, 2)
    } catch (error) {
        console.error('Analysis error:', error)
        analysisResult.value = 'Error performing analysis'
    } finally {
        analysisLoading.value = false
    }
}

const analyzeAllCustomers = async () => {
    showAnalysisModal.value = true
    analysisLoading.value = true
    analysisResult.value = null

    try {
        const response = await fetch(`/api/ai/customers/batch-analyze`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${window.apiToken}`,
            },
            body: JSON.stringify({
                limit: 10,
                criteria: 'high_value'
            })
        })

        const data = await response.json()
        analysisResult.value = data.analysis || 'No analysis available'
    } catch (error) {
        console.error('Batch analysis error:', error)
        analysisResult.value = 'Error performing batch analysis'
    } finally {
        analysisLoading.value = false
    }
}
</script>
