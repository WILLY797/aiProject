<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Navigation from '@/components/Navigation.vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});

const pages = [
    {
        name: 'Home Page',
        route: '/',
        description: 'Modern landing page with AI-powered branding, hero section, features, stats, and call-to-action.',
        features: ['Hero Section with Animations', 'Stats Display', 'Feature Cards', 'Floating Elements', 'Gradient Backgrounds']
    },
    {
        name: 'Features Page',
        route: '/features',
        description: 'Comprehensive features showcase with testimonials, pricing plans, and detailed feature explanations.',
        features: ['Feature Grid', 'Customer Testimonials', 'Pricing Tables', 'FAQ Section', 'Interactive Elements']
    },
    {
        name: 'About Page',
        route: '/about',
        description: 'Company story with timeline, team showcase, values, and mission statement.',
        features: ['Company Timeline', 'Team Profiles', 'Core Values', 'Mission Statement', 'Achievement Stats']
    },
    {
        name: 'Contact Page',
        route: '/contact',
        description: 'Professional contact form with business information and FAQ section.',
        features: ['Contact Form', 'Business Info Cards', 'FAQ Section', 'Contact Methods', 'Form Validation']
    }
];
</script>

<template>
    <Head title="Demo - Landing Pages" />

    <div class="min-h-screen bg-gradient-to-br from-primary-50 via-white to-secondary-50">
        <!-- Navigation -->
        <Navigation :canLogin="canLogin" :canRegister="canRegister" currentRoute="demo" />

        <!-- Content with top padding for fixed nav -->
        <div class="pt-16">
            <!-- Hero Section -->
            <section class="px-6 py-20 lg:px-8">
                <div class="mx-auto max-w-4xl text-center">
                    <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-6">
                        Landing Pages
                        <span class="bg-gradient-to-r from-primary-500 to-secondary-400 bg-clip-text text-transparent">
                            Demo
                        </span>
                    </h1>
                    <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                        Explore our beautifully designed landing pages built with Vue.js, Tailwind CSS,
                        and your custom yellow brand colors. Each page features modern design patterns,
                        smooth animations, and responsive layouts.
                    </p>

                    <div class="flex flex-wrap gap-3 justify-center">
                        <span class="px-3 py-1 bg-primary-100 text-primary-800 rounded-full text-sm font-medium">Vue 3</span>
                        <span class="px-3 py-1 bg-primary-100 text-primary-800 rounded-full text-sm font-medium">Tailwind CSS</span>
                        <span class="px-3 py-1 bg-primary-100 text-primary-800 rounded-full text-sm font-medium">Laravel 11</span>
                        <span class="px-3 py-1 bg-primary-100 text-primary-800 rounded-full text-sm font-medium">Inertia.js</span>
                        <span class="px-3 py-1 bg-primary-100 text-primary-800 rounded-full text-sm font-medium">Responsive Design</span>
                    </div>
                </div>
            </section>

            <!-- Pages Showcase -->
            <section class="px-6 py-20 lg:px-8">
                <div class="mx-auto max-w-7xl">
                    <div class="grid lg:grid-cols-2 gap-12">
                        <div v-for="page in pages" :key="page.name"
                             class="group bg-white rounded-2xl shadow-card hover:shadow-glow-yellow transition-all duration-300 transform hover:-translate-y-2 overflow-hidden">
                            <!-- Page Preview -->
                            <div class="aspect-video bg-gradient-to-br from-primary-100 to-secondary-100 relative overflow-hidden">
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="text-center">
                                        <div class="text-6xl mb-4">🎨</div>
                                        <p class="text-gray-600">{{ page.name }} Preview</p>
                                    </div>
                                </div>
                                <!-- Preview overlay -->
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-all duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                                    <Link :href="page.route"
                                          class="px-6 py-3 bg-white text-primary-600 font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                                        View Page
                                    </Link>
                                </div>
                            </div>

                            <!-- Page Info -->
                            <div class="p-8">
                                <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ page.name }}</h3>
                                <p class="text-gray-600 mb-6 leading-relaxed">{{ page.description }}</p>

                                <!-- Features -->
                                <div class="space-y-2 mb-6">
                                    <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wide">Features</h4>
                                    <div class="flex flex-wrap gap-2">
                                        <span v-for="feature in page.features" :key="feature"
                                              class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">
                                            {{ feature }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <Link :href="page.route"
                                      class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary-500 to-primary-600 text-white font-semibold rounded-lg hover:from-primary-600 hover:to-primary-700 transition-all duration-300 shadow-lg group-hover:shadow-glow-yellow">
                                    Explore {{ page.name }}
                                    <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Design Features -->
            <section class="px-6 py-20 bg-white/50 backdrop-blur-sm">
                <div class="mx-auto max-w-7xl">
                    <div class="text-center mb-16">
                        <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                            Design Features
                        </h2>
                        <p class="mx-auto max-w-2xl text-xl text-gray-600">
                            Every page is crafted with attention to detail, modern design principles, and your brand identity.
                        </p>
                    </div>

                    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                        <div class="text-center p-6 bg-white rounded-xl shadow-card hover:shadow-glow-yellow transition-all duration-300">
                            <div class="text-4xl mb-4">🎨</div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Custom Brand Colors</h3>
                            <p class="text-gray-600 text-sm">Beautiful yellow and bright yellow color palette throughout all designs.</p>
                        </div>

                        <div class="text-center p-6 bg-white rounded-xl shadow-card hover:shadow-glow-yellow transition-all duration-300">
                            <div class="text-4xl mb-4">📱</div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Fully Responsive</h3>
                            <p class="text-gray-600 text-sm">Perfect display on desktop, tablet, and mobile devices.</p>
                        </div>

                        <div class="text-center p-6 bg-white rounded-xl shadow-card hover:shadow-glow-yellow transition-all duration-300">
                            <div class="text-4xl mb-4">⚡</div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Smooth Animations</h3>
                            <p class="text-gray-600 text-sm">Engaging animations and transitions for better user experience.</p>
                        </div>

                        <div class="text-center p-6 bg-white rounded-xl shadow-card hover:shadow-glow-yellow transition-all duration-300">
                            <div class="text-4xl mb-4">🚀</div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Performance Optimized</h3>
                            <p class="text-gray-600 text-sm">Fast loading times with optimized code and assets.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Navigation Demo -->
            <section class="px-6 py-20 lg:px-8">
                <div class="mx-auto max-w-4xl text-center">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                        Quick Navigation
                    </h2>
                    <p class="text-lg text-gray-600 mb-8">
                        Jump directly to any landing page to see the full experience.
                    </p>

                    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <Link href="/"
                              class="p-6 bg-gradient-to-br from-primary-100 to-primary-200 rounded-xl hover:from-primary-200 hover:to-primary-300 transition-all duration-300 transform hover:scale-105 shadow-card hover:shadow-glow-yellow">
                            <div class="text-2xl mb-2">🏠</div>
                            <div class="font-semibold text-gray-900">Home</div>
                        </Link>

                        <Link href="/features"
                              class="p-6 bg-gradient-to-br from-secondary-100 to-secondary-200 rounded-xl hover:from-secondary-200 hover:to-secondary-300 transition-all duration-300 transform hover:scale-105 shadow-card hover:shadow-glow-yellow">
                            <div class="text-2xl mb-2">⭐</div>
                            <div class="font-semibold text-gray-900">Features</div>
                        </Link>

                        <Link href="/about"
                              class="p-6 bg-gradient-to-br from-primary-100 to-secondary-100 rounded-xl hover:from-primary-200 hover:to-secondary-200 transition-all duration-300 transform hover:scale-105 shadow-card hover:shadow-glow-yellow">
                            <div class="text-2xl mb-2">👥</div>
                            <div class="font-semibold text-gray-900">About</div>
                        </Link>

                        <Link href="/contact"
                              class="p-6 bg-gradient-to-br from-secondary-100 to-primary-100 rounded-xl hover:from-secondary-200 hover:to-primary-200 transition-all duration-300 transform hover:scale-105 shadow-card hover:shadow-glow-yellow">
                            <div class="text-2xl mb-2">📞</div>
                            <div class="font-semibold text-gray-900">Contact</div>
                        </Link>
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
                            © 2025 AI Project. Beautiful landing pages with Vue.js and Tailwind CSS.
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</template>
