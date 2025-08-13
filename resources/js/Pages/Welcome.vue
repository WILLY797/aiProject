<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import Navigation from '@/components/Navigation.vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

const features = ref([
    {
        icon: '🤖',
        title: 'AI-Powered Intelligence',
        description: 'Advanced AI service for product descriptions, customer analytics, and intelligent insights.'
    },
    {
        icon: '📊',
        title: 'Smart Analytics',
        description: 'Real-time data analysis and pattern recognition to drive your business decisions.'
    },
    {
        icon: '🔄',
        title: 'Seamless Integration',
        description: 'Effortless integration with Equinox API for streamlined workflow management.'
    },
    {
        icon: '⚡',
        title: 'Lightning Fast',
        description: 'Optimized performance with Laravel 11 and Vue 3 for exceptional user experience.'
    }
]);

const stats = ref([
    { number: '50K+', label: 'Products Managed' },
    { number: '99.9%', label: 'Uptime' },
    { number: '24/7', label: 'AI Support' },
    { number: '10x', label: 'Faster Processing' }
]);

const isVisible = ref(false);

onMounted(() => {
    isVisible.value = true;
});
</script>

<template>
    <Head title="Welcome - AI Project" />

    <div class="min-h-screen bg-gradient-to-br from-primary-50 via-white to-secondary-50">
        <!-- Navigation -->
        <Navigation :canLogin="canLogin" :canRegister="canRegister" currentRoute="home" />

        <!-- Content with top padding for fixed nav -->
        <div class="pt-16">
            <!-- Hero Section -->
            <section class="relative px-6 py-20 lg:px-8">
                <div class="mx-auto max-w-7xl">
                    <div class="text-center">
                        <h1 class="text-5xl md:text-7xl lg:text-8xl font-bold text-gray-900 mb-8 animate-fade-in">
                            <span class="bg-gradient-to-r from-primary-500 to-secondary-400 bg-clip-text text-transparent">
                                AI-Powered
                            </span>
                            <br>
                            Business Intelligence
                        </h1>

                        <p class="mx-auto max-w-3xl text-xl md:text-2xl text-gray-600 mb-12 animate-slide-up">
                            Transform your business with cutting-edge AI technology. Smart analytics,
                            automated insights, and seamless integration all in one powerful platform.
                        </p>

                        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-slide-up">
                            <Link v-if="canRegister"
                                  :href="route('register')"
                                  class="group relative px-8 py-4 bg-gradient-to-r from-primary-500 to-primary-600 text-white text-lg font-semibold rounded-2xl hover:from-primary-600 hover:to-primary-700 transition-all duration-300 shadow-glow-yellow-lg hover:shadow-glow-yellow-lg transform hover:scale-105">
                                <span class="relative z-10">Start Free Trial</span>
                                <div class="absolute inset-0 bg-gradient-to-r from-primary-400 to-primary-500 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </Link>

                            <button class="px-8 py-4 border-2 border-primary-500 text-primary-600 text-lg font-semibold rounded-2xl hover:bg-primary-50 transition-all duration-300">
                                Watch Demo
                            </button>
                        </div>
                    </div>

                    <!-- Floating Elements -->
                    <div class="absolute top-20 left-10 w-20 h-20 bg-primary-200 rounded-full opacity-60 animate-float"></div>
                    <div class="absolute top-40 right-20 w-16 h-16 bg-secondary-300 rounded-full opacity-40 animate-float" style="animation-delay: 1s;"></div>
                    <div class="absolute bottom-20 left-1/4 w-12 h-12 bg-primary-300 rounded-full opacity-50 animate-float" style="animation-delay: 2s;"></div>
                </div>
            </section>

            <!-- Stats Section -->
            <section class="px-6 py-16 bg-white/50 backdrop-blur-sm">
                <div class="mx-auto max-w-7xl">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                        <div v-for="stat in stats" :key="stat.label" class="text-center">
                            <div class="text-4xl md:text-5xl font-bold text-primary-600 mb-2">
                                {{ stat.number }}
                            </div>
                            <div class="text-gray-600 font-medium">{{ stat.label }}</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Features Section -->
            <section class="px-6 py-20 lg:px-8">
                <div class="mx-auto max-w-7xl">
                    <div class="text-center mb-16">
                        <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                            Powerful Features
                        </h2>
                        <p class="mx-auto max-w-2xl text-xl text-gray-600">
                            Everything you need to transform your business with AI-driven insights and automation.
                        </p>
                    </div>

                    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                        <div v-for="feature in features" :key="feature.title"
                             class="group p-8 bg-white rounded-2xl shadow-card hover:shadow-glow-yellow transition-all duration-300 transform hover:-translate-y-2">
                            <div class="text-4xl mb-4 group-hover:animate-bounce-subtle">{{ feature.icon }}</div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">{{ feature.title }}</h3>
                            <p class="text-gray-600 leading-relaxed">{{ feature.description }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA Section -->
            <section class="px-6 py-20 bg-gradient-to-r from-primary-500 to-secondary-400">
                <div class="mx-auto max-w-4xl text-center">
                    <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                        Ready to Transform Your Business?
                    </h2>
                    <p class="text-xl text-primary-100 mb-8">
                        Join thousands of companies already using our AI platform to drive growth and efficiency.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <Link v-if="canRegister"
                              :href="route('register')"
                              class="px-8 py-4 bg-white text-primary-600 text-lg font-semibold rounded-2xl hover:bg-primary-50 transition-all duration-300 shadow-lg">
                            Get Started Now
                        </Link>
                        <button class="px-8 py-4 border-2 border-white text-white text-lg font-semibold rounded-2xl hover:bg-white/10 transition-all duration-300">
                            Contact Sales
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
                        <p class="text-gray-400 mb-4">
                            Powered by Laravel {{ laravelVersion }} & PHP {{ phpVersion }}
                        </p>
                        <div class="text-sm text-gray-500">
                            © 2025 AI Project. Built with ❤️ and cutting-edge technology.
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</template>

<style scoped>
/* Additional custom styles if needed */
.animate-fade-in {
    animation: fadeIn 0.8s ease-in-out;
}

.animate-slide-up {
    animation: slideUp 0.8s ease-out 0.2s both;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

