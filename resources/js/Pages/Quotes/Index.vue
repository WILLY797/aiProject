<template>
    <div class="min-h-screen bg-gradient-to-br from-yellow-50 to-yellow-100">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b border-yellow-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <h1 class="text-3xl font-bold text-gray-900">Quote Management</h1>
                    <Link :href="route('quotes.create')"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                    Create Quote
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
                            <option value="accepted">Accepted</option>
                            <option value="rejected">Rejected</option>
                            <option value="expired">Expired</option>
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
                            <p class="text-sm font-medium text-gray-600">Total Quotes</p>
                            <p class="text-2xl font-bold text-gray-900">{{ stats.total }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-yellow-200 p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Accepted</p>
                            <p class="text-2xl font-bold text-gray-900">{{ stats.accepted }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-yellow-200 p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        <div class="p-2 bg-purple-100 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Conversion Rate</p>
                            <p class="text-2xl font-bold text-gray-900">{{ stats.conversion_rate }}%</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quotes Table -->
            <div class="bg-white rounded-lg shadow-sm border border-yellow-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Quotes</h3>
                    <button @click="generateQuoteInsights"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        AI Insights
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Quote ID</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Customer</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Amount</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Valid Until</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Created</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="quote in quotes.data" :key="quote.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #{{ quote.equinox_id || quote.id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ quote.account?.name || 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ quote.account?.code || '' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="getStatusClass(quote.status)"
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                                        {{ quote.status || 'draft' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    £{{ quote.quote_total_gross?.toLocaleString() || quote.total?.toLocaleString() ||
                                    '0.00' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span :class="isExpired(quote.expires_at) ? 'text-red-600' : 'text-gray-900'">
                                        {{ formatDate(quote.expires_at) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ formatDate(quote.created_at) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <Link :href="route('quotes.show', quote.id)"
                                        class="text-yellow-600 hover:text-yellow-900">
                                    View
                                    </Link>
                                    <button @click="convertToOrder(quote)" class="text-green-600 hover:text-green-900"
                                        v-if="quote.status === 'accepted'">
                                        Convert
                                    </button>
                                    <button @click="getRecommendations(quote)"
                                        class="text-blue-600 hover:text-blue-900">
                                        AI Suggest
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="quotes.links" class="px-6 py-3 border-t border-gray-200">
                    <nav class="flex justify-between">
                        <div class="flex space-x-2">
                            <Link v-for="link in quotes.links" :key="link.label" :href="link.url" :class="[
                                'px-3 py-2 text-sm rounded-md',
                                link.active ? 'bg-yellow-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'
                            ]" v-html="link.label">
                            </Link>
                        </div>
                    </nav>
                </div>
            </div>

            <!-- Performance Analytics -->
            <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white rounded-lg shadow-sm border border-yellow-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Quote Performance</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Average Quote Value</span>
                            <span class="text-sm font-medium text-gray-900">£{{
                                analytics.avg_quote_value?.toLocaleString() || '0' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Average Response Time</span>
                            <span class="text-sm font-medium text-gray-900">{{ analytics.avg_response_time || '0' }}
                                days</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Win Rate</span>
                            <span class="text-sm font-medium text-gray-900">{{ analytics.win_rate || '0' }}%</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-yellow-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Top Products Quoted</h3>
                    <div class="space-y-3">
                        <div v-for="product in analytics.top_products" :key="product.id"
                            class="flex justify-between items-center">
                            <div class="flex-1">
                                <div class="text-sm font-medium text-gray-900">{{ product.name }}</div>
                                <div class="text-xs text-gray-500">{{ product.sku }}</div>
                            </div>
                            <div class="text-sm text-gray-600">{{ product.quote_count }} quotes</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- AI Modal -->
        <div v-if="showAIModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">AI Insights & Recommendations</h3>
                        <button @click="showAIModal = false" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div v-if="aiLoading" class="text-center py-8">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-yellow-500 mx-auto"></div>
                        <p class="mt-4 text-gray-600">Generating insights...</p>
                    </div>
                    <div v-else-if="aiResult" class="space-y-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-2">AI Analysis</h4>
                            <pre class="text-sm text-gray-700 whitespace-pre-wrap">{{ aiResult }}</pre>
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
    quotes: Object,
    accounts: Array,
    filters: Object,
    stats: Object,
    analytics: Object
})

const showAIModal = ref(false)
const aiLoading = ref(false)
const aiResult = ref(null)

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
        accepted: 'bg-green-100 text-green-800',
        rejected: 'bg-red-100 text-red-800',
        expired: 'bg-red-100 text-red-800'
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

const isExpired = (dateString) => {
    if (!dateString) return false
    return new Date(dateString) < new Date()
}

const applyFilters = () => {
    router.get(route('quotes.index'), filters, { preserveState: true })
}

const convertToOrder = (quote) => {
    router.post(route('orders.create'), {
        quote_id: quote.id
    })
}

const getRecommendations = async (quote) => {
    if (!quote.account?.id) return

    showAIModal.value = true
    aiLoading.value = true
    aiResult.value = null

    try {
        const response = await fetch(`/api/ai/customers/${quote.account.id}/recommendations`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${window.apiToken}`,
            },
            body: JSON.stringify({ limit: 5 })
        })

        const data = await response.json()
        aiResult.value = JSON.stringify(data.recommendations, null, 2)
    } catch (error) {
        console.error('Recommendations error:', error)
        aiResult.value = 'Error generating recommendations'
    } finally {
        aiLoading.value = false
    }
}

const generateQuoteInsights = async () => {
    showAIModal.value = true
    aiLoading.value = true
    aiResult.value = null

    try {
        const response = await fetch(`/api/ai/quotes/analyze`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${window.apiToken}`,
            },
            body: JSON.stringify({
                period_days: 90,
                analysis_type: 'performance'
            })
        })

        const data = await response.json()
        aiResult.value = data.analysis || 'No insights available'
    } catch (error) {
        console.error('Insights error:', error)
        aiResult.value = 'Error generating insights'
    } finally {
        aiLoading.value = false
    }
}
</script>
