<template>
    <div class="min-h-screen bg-gradient-to-br from-yellow-50 to-yellow-100">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b border-yellow-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <div>
                        <Link :href="route('orders.index')"
                            class="text-yellow-600 hover:text-yellow-800 text-sm font-medium">
                        ← Back to Orders
                        </Link>
                        <h1 class="text-3xl font-bold text-gray-900 mt-2">Order #{{ order.equinox_id || order.id }}</h1>
                    </div>
                    <div class="flex space-x-3">
                        <button @click="generateInvoice"
                            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-medium">
                            Generate Invoice
                        </button>
                        <button @click="syncWithEquinox"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium">
                            Sync with Equinox
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Order Summary -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm border border-yellow-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Order Details</h2>

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <select v-model="order.status" @change="updateStatus"
                                    class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                                    <option value="pending">Pending</option>
                                    <option value="processing">Processing</option>
                                    <option value="shipped">Shipped</option>
                                    <option value="delivered">Delivered</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Order Date</label>
                                <p class="mt-1 text-sm text-gray-900">{{ formatDate(order.created_at) }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Customer</label>
                                <p class="mt-1 text-sm text-gray-900">{{ order.account?.name || 'N/A' }}</p>
                                <p class="text-xs text-gray-500">{{ order.account?.code || '' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Total Amount</label>
                                <p class="mt-1 text-lg font-semibold text-gray-900">£{{
                                    order.order_total_gross?.toLocaleString() || '0.00' }}</p>
                            </div>
                        </div>

                        <!-- Shipping Address -->
                        <div class="mt-6" v-if="order.shipping_address">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Shipping Address</h3>
                            <div class="text-sm text-gray-900 bg-gray-50 rounded p-3">
                                <p v-if="order.shipping_address.name">{{ order.shipping_address.name }}</p>
                                <p v-if="order.shipping_address.company">{{ order.shipping_address.company }}</p>
                                <p>{{ order.shipping_address.address1 }}</p>
                                <p v-if="order.shipping_address.address2">{{ order.shipping_address.address2 }}</p>
                                <p>{{ order.shipping_address.postcode }} {{ order.shipping_address.country }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Timeline -->
                <div class="bg-white rounded-lg shadow-sm border border-yellow-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Order Timeline</h2>
                    <div class="space-y-4">
                        <div v-for="(event, index) in timeline" :key="index" class="flex items-center">
                            <div :class="[
                                'w-3 h-3 rounded-full mr-3',
                                event.completed ? 'bg-green-500' : event.current ? 'bg-yellow-500' : 'bg-gray-300'
                            ]"></div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ event.title }}</p>
                                <p class="text-xs text-gray-500" v-if="event.date">{{ event.date }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow-sm border border-yellow-200 overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Order Items</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Product</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    SKU</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Quantity</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Unit Price</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="item in order.order_lines" :key="item.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ item.description ||
                                        item.product?.name }}</div>
                                    <div class="text-sm text-gray-500" v-if="item.product?.mpn">MPN: {{ item.product.mpn
                                        }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ item.sku || item.product?.sku }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ item.quantity }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    £{{ item.unit_price?.toLocaleString() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                    £{{ (item.quantity * item.unit_price)?.toLocaleString() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button @click="getProductDescription(item)"
                                        class="text-blue-600 hover:text-blue-900">
                                        AI Description
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Order Totals -->
                <div class="bg-gray-50 px-6 py-4">
                    <div class="flex justify-end">
                        <div class="w-64 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal:</span>
                                <span class="text-gray-900">£{{ order.order_total_net?.toLocaleString() || '0.00'
                                    }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">VAT:</span>
                                <span class="text-gray-900">£{{ order.order_total_vat?.toLocaleString() || '0.00'
                                    }}</span>
                            </div>
                            <div class="flex justify-between text-lg font-semibold border-t pt-2">
                                <span class="text-gray-900">Total:</span>
                                <span class="text-gray-900">£{{ order.order_total_gross?.toLocaleString() || '0.00'
                                    }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes and Comments -->
            <div class="bg-white rounded-lg shadow-sm border border-yellow-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Notes & Comments</h3>
                <div class="space-y-4">
                    <div v-if="order.notes" class="bg-gray-50 rounded p-3">
                        <p class="text-sm text-gray-900">{{ order.notes }}</p>
                    </div>
                    <div>
                        <textarea v-model="newNote" placeholder="Add a note..."
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" rows="3"></textarea>
                        <button @click="addNote"
                            class="mt-2 bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded text-sm font-medium">
                            Add Note
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- AI Modal -->
        <div v-if="showAIModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">AI Generated Description</h3>
                        <button @click="showAIModal = false" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div v-if="aiLoading" class="text-center py-8">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-yellow-500 mx-auto"></div>
                        <p class="mt-4 text-gray-600">Generating description...</p>
                    </div>
                    <div v-else-if="aiResult" class="space-y-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-2">Generated Description</h4>
                            <p class="text-sm text-gray-700">{{ aiResult }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'

const props = defineProps({
    order: Object
})

const showAIModal = ref(false)
const aiLoading = ref(false)
const aiResult = ref(null)
const newNote = ref('')

const timeline = computed(() => {
    const status = props.order.status || 'pending'
    const events = [
        { title: 'Order Placed', completed: true, date: formatDate(props.order.created_at) },
        { title: 'Processing', completed: ['processing', 'shipped', 'delivered'].includes(status), current: status === 'processing' },
        { title: 'Shipped', completed: ['shipped', 'delivered'].includes(status), current: status === 'shipped' },
        { title: 'Delivered', completed: status === 'delivered', current: status === 'delivered' }
    ]

    if (status === 'cancelled') {
        events.push({ title: 'Cancelled', completed: true, current: true })
    }

    return events
})

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-GB', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}

const updateStatus = () => {
    router.patch(route('orders.update', props.order.id), {
        status: props.order.status
    })
}

const generateInvoice = () => {
    router.post(route('invoices.create'), {
        order_id: props.order.id
    })
}

const syncWithEquinox = async () => {
    try {
        const response = await fetch(`/api/orders/${props.order.id}/sync`, {
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
        console.error('Sync error:', error)
    }
}

const getProductDescription = async (item) => {
    if (!item.product?.id) return

    showAIModal.value = true
    aiLoading.value = true
    aiResult.value = null

    try {
        const response = await fetch(`/api/ai/products/${item.product.id}/description`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${window.apiToken}`,
            },
            body: JSON.stringify({ style: 'professional' })
        })

        const data = await response.json()
        aiResult.value = data.ai_generated_description
    } catch (error) {
        console.error('AI description error:', error)
        aiResult.value = 'Error generating description'
    } finally {
        aiLoading.value = false
    }
}

const addNote = () => {
    if (!newNote.value.trim()) return

    router.patch(route('orders.update', props.order.id), {
        notes: (props.order.notes || '') + '\n\n' + new Date().toLocaleString() + ': ' + newNote.value
    }, {
        onSuccess: () => {
            newNote.value = ''
        }
    })
}
</script>
