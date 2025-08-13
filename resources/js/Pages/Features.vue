<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const features = ref([
    {
        icon: '🧠',
        title: 'Advanced AI Analytics',
        description: 'Leverage cutting-edge machine learning algorithms to analyze customer behavior, predict trends, and optimize your business strategies.',
        benefits: ['Predictive Analytics', 'Behavior Analysis', 'Trend Forecasting', 'Real-time Insights']
    },
    {
        icon: '🔗',
        title: 'Seamless API Integration',
        description: 'Connect effortlessly with Equinox API and other third-party services through our robust integration framework.',
        benefits: ['One-click Setup', 'Real-time Sync', 'Secure Connections', 'Custom Webhooks']
    },
    {
        icon: '📈',
        title: 'Smart Product Management',
        description: 'AI-powered product descriptions, automated categorization, and intelligent inventory management.',
        benefits: ['Auto Descriptions', 'Smart Categories', 'Inventory Alerts', 'Price Optimization']
    },
    {
        icon: '💬',
        title: 'Intelligent Customer Support',
        description: 'AI-driven customer support with automated responses, sentiment analysis, and personalized interactions.',
        benefits: ['24/7 Support', 'Sentiment Analysis', 'Auto Responses', 'Personal Touch']
    },
    {
        icon: '📊',
        title: 'Advanced Reporting',
        description: 'Generate comprehensive reports with AI insights, custom dashboards, and exportable analytics.',
        benefits: ['Custom Dashboards', 'Export Options', 'Scheduled Reports', 'Visual Analytics']
    },
    {
        icon: '🔒',
        title: 'Enterprise Security',
        description: 'Bank-level security with end-to-end encryption, secure authentication, and compliance standards.',
        benefits: ['End-to-end Encryption', 'Multi-factor Auth', 'Compliance Ready', 'Audit Trails']
    }
]);

const testimonials = ref([
    {
        name: 'Sarah Johnson',
        role: 'CEO, TechCorp',
        avatar: '👩‍💼',
        quote: 'This AI platform transformed our business operations. We saw 40% improvement in efficiency within the first month.',
        rating: 5
    },
    {
        name: 'Michael Chen',
        role: 'CTO, InnovateLab',
        avatar: '👨‍💻',
        quote: 'The integration was seamless and the AI insights are incredibly accurate. Best investment we\'ve made.',
        rating: 5
    },
    {
        name: 'Emily Rodriguez',
        role: 'Operations Director',
        avatar: '👩‍🔬',
        quote: 'Customer satisfaction increased by 60% after implementing the AI-powered support system.',
        rating: 5
    }
]);

const pricingPlans = ref([
    {
        name: 'Starter',
        price: '$29',
        period: '/month',
        description: 'Perfect for small businesses getting started with AI',
        features: [
            'Up to 1,000 AI requests/month',
            'Basic analytics dashboard',
            'Email support',
            'Standard integrations',
            '1 user account'
        ],
        popular: false,
        buttonText: 'Start Free Trial',
        buttonClass: 'border-2 border-primary-500 text-primary-600 hover:bg-primary-50'
    },
    {
        name: 'Professional',
        price: '$99',
        period: '/month',
        description: 'Ideal for growing businesses that need more power',
        features: [
            'Up to 10,000 AI requests/month',
            'Advanced analytics & reporting',
            'Priority support',
            'All integrations included',
            'Up to 10 user accounts',
            'Custom AI model training'
        ],
        popular: true,
        buttonText: 'Get Started',
        buttonClass: 'bg-gradient-to-r from-primary-500 to-primary-600 text-white hover:from-primary-600 hover:to-primary-700 shadow-glow-yellow'
    },
    {
        name: 'Enterprise',
        price: 'Custom',
        period: '',
        description: 'For large organizations with custom requirements',
        features: [
            'Unlimited AI requests',
            'Custom analytics solutions',
            'Dedicated account manager',
            'Custom integrations',
            'Unlimited user accounts',
            'On-premise deployment option',
            'SLA guarantee'
        ],
        popular: false,
        buttonText: 'Contact Sales',
        buttonClass: 'border-2 border-gray-300 text-gray-700 hover:bg-gray-50'
    }
]);
</script>

<template>
    <Head title="Features - AI Project" />

    <div class="min-h-screen bg-gradient-to-br from-primary-50 via-white to-secondary-50">
        <!-- Navigation -->
        <nav class="relative z-10 px-6 py-4 lg:px-8 bg-white/80 backdrop-blur-sm border-b border-primary-100">
            <div class="mx-auto max-w-7xl">
                <div class="flex items-center justify-between">
                    <Link href="/" class="flex items-center space-x-2">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center shadow-glow-yellow">
                            <span class="text-white font-bold text-lg">AI</span>
                        </div>
                        <span class="text-xl font-bold text-gray-900">Project</span>
                    </Link>

                    <div class="hidden md:flex items-center space-x-8">
                        <Link href="/" class="text-gray-600 hover:text-primary-600 transition-colors">Home</Link>
                        <Link href="#features" class="text-primary-600 font-medium">Features</Link>
                        <Link href="#pricing" class="text-gray-600 hover:text-primary-600 transition-colors">Pricing</Link>
                        <Link href="#testimonials" class="text-gray-600 hover:text-primary-600 transition-colors">Reviews</Link>
                    </div>

                    <div class="flex items-center space-x-4">
                        <Link :href="route('login')"
                              class="text-gray-700 font-medium hover:text-primary-600 transition-colors">
                            Log in
                        </Link>
                        <Link :href="route('register')"
                              class="rounded-lg px-6 py-2.5 bg-primary-500 text-white font-medium hover:bg-primary-600 transition-all duration-200 shadow-glow-yellow">
                            Get Started
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="px-6 py-20 lg:px-8">
            <div class="mx-auto max-w-4xl text-center">
                <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-6">
                    Powerful Features for
                    <span class="bg-gradient-to-r from-primary-500 to-secondary-400 bg-clip-text text-transparent">
                        Modern Business
                    </span>
                </h1>
                <p class="text-xl text-gray-600 mb-8">
                    Discover how our AI-powered platform can transform your business operations
                    with cutting-edge features designed for the future.
                </p>
            </div>
        </section>

        <!-- Features Grid -->
        <section id="features" class="px-6 py-20 lg:px-8">
            <div class="mx-auto max-w-7xl">
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div v-for="feature in features" :key="feature.title"
                         class="group p-8 bg-white rounded-2xl shadow-card hover:shadow-glow-yellow transition-all duration-300 transform hover:-translate-y-2">
                        <div class="text-5xl mb-6 group-hover:animate-bounce-subtle">{{ feature.icon }}</div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ feature.title }}</h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">{{ feature.description }}</p>

                        <div class="space-y-2">
                            <div v-for="benefit in feature.benefits" :key="benefit"
                                 class="flex items-center text-sm text-gray-500">
                                <span class="w-2 h-2 bg-primary-400 rounded-full mr-3"></span>
                                {{ benefit }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials -->
        <section id="testimonials" class="px-6 py-20 bg-white/50 backdrop-blur-sm">
            <div class="mx-auto max-w-7xl">
                <div class="text-center mb-16">
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                        What Our Customers Say
                    </h2>
                    <p class="mx-auto max-w-2xl text-xl text-gray-600">
                        Join thousands of satisfied customers who have transformed their business with our AI platform.
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <div v-for="testimonial in testimonials" :key="testimonial.name"
                         class="p-8 bg-white rounded-2xl shadow-card hover:shadow-glow-yellow transition-all duration-300">
                        <div class="flex items-center mb-6">
                            <div class="text-4xl mr-4">{{ testimonial.avatar }}</div>
                            <div>
                                <div class="font-bold text-gray-900">{{ testimonial.name }}</div>
                                <div class="text-sm text-gray-500">{{ testimonial.role }}</div>
                            </div>
                        </div>

                        <div class="flex mb-4">
                            <span v-for="i in testimonial.rating" :key="i" class="text-primary-400 text-lg">★</span>
                        </div>

                        <p class="text-gray-600 leading-relaxed italic">"{{ testimonial.quote }}"</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pricing -->
        <section id="pricing" class="px-6 py-20 lg:px-8">
            <div class="mx-auto max-w-7xl">
                <div class="text-center mb-16">
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                        Choose Your Plan
                    </h2>
                    <p class="mx-auto max-w-2xl text-xl text-gray-600">
                        Start with our free trial and scale as your business grows. No hidden fees, cancel anytime.
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <div v-for="plan in pricingPlans" :key="plan.name"
                         :class="[
                             'relative p-8 bg-white rounded-2xl shadow-card transition-all duration-300',
                             plan.popular ? 'ring-2 ring-primary-500 shadow-glow-yellow-lg transform scale-105' : 'hover:shadow-glow-yellow hover:-translate-y-2'
                         ]">
                        <div v-if="plan.popular"
                             class="absolute -top-4 left-1/2 transform -translate-x-1/2 px-4 py-2 bg-gradient-to-r from-primary-500 to-primary-600 text-white text-sm font-medium rounded-full">
                            Most Popular
                        </div>

                        <div class="text-center mb-8">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ plan.name }}</h3>
                            <div class="flex items-baseline justify-center mb-2">
                                <span class="text-4xl font-bold text-gray-900">{{ plan.price }}</span>
                                <span class="text-gray-500 ml-1">{{ plan.period }}</span>
                            </div>
                            <p class="text-gray-600">{{ plan.description }}</p>
                        </div>

                        <ul class="space-y-4 mb-8">
                            <li v-for="feature in plan.features" :key="feature"
                                class="flex items-start">
                                <span class="w-5 h-5 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">✓</span>
                                <span class="text-gray-600">{{ feature }}</span>
                            </li>
                        </ul>

                        <button :class="[
                            'w-full py-3 px-6 rounded-lg font-semibold transition-all duration-300',
                            plan.buttonClass
                        ]">
                            {{ plan.buttonText }}
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="px-6 py-20 bg-gradient-to-r from-primary-500 to-secondary-400">
            <div class="mx-auto max-w-4xl text-center">
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                    Ready to Get Started?
                </h2>
                <p class="text-xl text-primary-100 mb-8">
                    Join thousands of companies already using our platform. Start your free trial today.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <Link :href="route('register')"
                          class="px-8 py-4 bg-white text-primary-600 text-lg font-semibold rounded-2xl hover:bg-primary-50 transition-all duration-300 shadow-lg">
                        Start Free Trial
                    </Link>
                    <button class="px-8 py-4 border-2 border-white text-white text-lg font-semibold rounded-2xl hover:bg-white/10 transition-all duration-300">
                        Schedule Demo
                    </button>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="px-6 py-12 bg-dark text-white">
            <div class="mx-auto max-w-7xl">
                <div class="text-center">
                    <div class="flex items-center justify-center space-x-2 mb-4">
                        <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center">
                            <span class="text-white font-bold text-sm">AI</span>
                        </div>
                        <span class="text-lg font-bold">Project</span>
                    </div>
                    <div class="text-sm text-gray-500">
                        © 2025 AI Project. Empowering businesses with intelligent solutions.
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
/* Smooth scrolling for anchor links */
html {
    scroll-behavior: smooth;
}
</style>
