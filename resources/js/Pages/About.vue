<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const stats = ref([
    { number: '50K+', label: 'Happy Customers' },
    { number: '99.9%', label: 'Uptime' },
    { number: '24/7', label: 'Support' },
    { number: '150+', label: 'Countries Served' }
]);

const timeline = ref([
    {
        year: '2020',
        title: 'Company Founded',
        description: 'Started with a vision to democratize AI for businesses of all sizes.',
        icon: '🚀'
    },
    {
        year: '2021',
        title: 'First AI Model',
        description: 'Launched our first AI-powered analytics platform with 100+ early adopters.',
        icon: '🧠'
    },
    {
        year: '2022',
        title: 'Series A Funding',
        description: 'Raised $10M to expand our AI capabilities and international presence.',
        icon: '💰'
    },
    {
        year: '2023',
        title: 'Global Expansion',
        description: 'Opened offices in 5 countries and reached 10,000+ customers worldwide.',
        icon: '🌍'
    },
    {
        year: '2024',
        title: 'Advanced AI Suite',
        description: 'Launched comprehensive AI suite with predictive analytics and automation.',
        icon: '⚡'
    },
    {
        year: '2025',
        title: 'Industry Leadership',
        description: 'Recognized as the leading AI platform for business intelligence and automation.',
        icon: '🏆'
    }
]);

const team = ref([
    {
        name: 'Alex Rodriguez',
        role: 'CEO & Co-founder',
        bio: 'Former VP of Engineering at Google, PhD in Machine Learning from MIT.',
        avatar: '👨‍💼',
        social: {
            linkedin: '#',
            twitter: '#'
        }
    },
    {
        name: 'Sarah Chen',
        role: 'CTO & Co-founder',
        bio: 'Ex-Tesla AI researcher, 15+ years in distributed systems and ML.',
        avatar: '👩‍💻',
        social: {
            linkedin: '#',
            twitter: '#'
        }
    },
    {
        name: 'Marcus Johnson',
        role: 'Head of Product',
        bio: 'Former Product Lead at Airbnb, expert in user experience and product strategy.',
        avatar: '👨‍🎨',
        social: {
            linkedin: '#',
            twitter: '#'
        }
    },
    {
        name: 'Emily Wang',
        role: 'Head of AI Research',
        bio: 'PhD in Computer Science from Stanford, published 50+ papers in AI journals.',
        avatar: '👩‍🔬',
        social: {
            linkedin: '#',
            twitter: '#'
        }
    },
    {
        name: 'David Kim',
        role: 'VP of Engineering',
        bio: 'Ex-Microsoft architect, specialist in scalable cloud infrastructure.',
        avatar: '👨‍💻',
        social: {
            linkedin: '#',
            twitter: '#'
        }
    },
    {
        name: 'Lisa Thompson',
        role: 'Head of Customer Success',
        bio: 'Former Director at Salesforce, passionate about customer experience.',
        avatar: '👩‍💼',
        social: {
            linkedin: '#',
            twitter: '#'
        }
    }
]);

const values = ref([
    {
        icon: '🎯',
        title: 'Innovation First',
        description: 'We push the boundaries of what\'s possible with AI to create breakthrough solutions.'
    },
    {
        icon: '🤝',
        title: 'Customer Obsession',
        description: 'Every decision we make starts with understanding and solving our customers\' real problems.'
    },
    {
        icon: '🔒',
        title: 'Trust & Security',
        description: 'We build products with privacy and security as fundamental design principles.'
    },
    {
        icon: '🌟',
        title: 'Excellence',
        description: 'We strive for excellence in everything we do, from code quality to customer support.'
    },
    {
        icon: '🌱',
        title: 'Continuous Learning',
        description: 'We embrace change and continuously evolve our skills and knowledge.'
    },
    {
        icon: '🤖',
        title: 'Human-Centric AI',
        description: 'We believe AI should augment human capabilities, not replace human judgment.'
    }
]);
</script>

<template>
    <Head title="About Us - AI Project" />

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
                        <Link href="/features" class="text-gray-600 hover:text-primary-600 transition-colors">Features</Link>
                        <Link href="/about" class="text-primary-600 font-medium">About</Link>
                        <Link href="#contact" class="text-gray-600 hover:text-primary-600 transition-colors">Contact</Link>
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
                    Building the Future of
                    <span class="bg-gradient-to-r from-primary-500 to-secondary-400 bg-clip-text text-transparent">
                        AI-Powered Business
                    </span>
                </h1>
                <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                    We're on a mission to democratize artificial intelligence and make it accessible
                    to businesses of all sizes. Our platform empowers companies to harness the power
                    of AI without the complexity.
                </p>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="px-6 py-16">
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

        <!-- Mission Section -->
        <section class="px-6 py-20 bg-white/50 backdrop-blur-sm">
            <div class="mx-auto max-w-7xl">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                            Our Mission
                        </h2>
                        <p class="text-xl text-gray-600 mb-6 leading-relaxed">
                            We believe that artificial intelligence should be a force for good, empowering
                            businesses to make better decisions, serve customers more effectively, and
                            create meaningful value in the world.
                        </p>
                        <p class="text-lg text-gray-600 leading-relaxed">
                            Our platform removes the barriers to AI adoption by providing intuitive tools,
                            powerful integrations, and expert support that makes advanced AI capabilities
                            accessible to everyone.
                        </p>
                    </div>
                    <div class="relative">
                        <div class="bg-gradient-to-br from-primary-100 to-secondary-100 rounded-2xl p-8 shadow-glow-yellow">
                            <div class="text-6xl mb-4 text-center">🎯</div>
                            <blockquote class="text-lg text-gray-700 italic text-center">
                                "To democratize AI and empower every business to harness the transformative
                                power of artificial intelligence."
                            </blockquote>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Timeline -->
        <section class="px-6 py-20 lg:px-8">
            <div class="mx-auto max-w-7xl">
                <div class="text-center mb-16">
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                        Our Journey
                    </h2>
                    <p class="mx-auto max-w-2xl text-xl text-gray-600">
                        From a small startup to a leading AI platform, here's how we've grown and evolved.
                    </p>
                </div>

                <div class="relative">
                    <!-- Timeline line -->
                    <div class="absolute left-1/2 transform -translate-x-1/2 h-full w-1 bg-gradient-to-b from-primary-300 to-secondary-300"></div>

                    <div class="space-y-12">
                        <div v-for="(item, index) in timeline" :key="item.year"
                             :class="[
                                 'relative flex items-center',
                                 index % 2 === 0 ? 'justify-start' : 'justify-end'
                             ]">
                            <div :class="[
                                'max-w-lg p-6 bg-white rounded-2xl shadow-card hover:shadow-glow-yellow transition-all duration-300',
                                index % 2 === 0 ? 'mr-8' : 'ml-8'
                            ]">
                                <div class="flex items-center mb-4">
                                    <span class="text-3xl mr-3">{{ item.icon }}</span>
                                    <div>
                                        <div class="text-2xl font-bold text-primary-600">{{ item.year }}</div>
                                        <div class="text-lg font-semibold text-gray-900">{{ item.title }}</div>
                                    </div>
                                </div>
                                <p class="text-gray-600">{{ item.description }}</p>
                            </div>

                            <!-- Timeline dot -->
                            <div class="absolute left-1/2 transform -translate-x-1/2 w-4 h-4 bg-primary-500 rounded-full border-4 border-white shadow-lg"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Values -->
        <section class="px-6 py-20 bg-white/50 backdrop-blur-sm">
            <div class="mx-auto max-w-7xl">
                <div class="text-center mb-16">
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                        Our Values
                    </h2>
                    <p class="mx-auto max-w-2xl text-xl text-gray-600">
                        The principles that guide everything we do and every decision we make.
                    </p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div v-for="value in values" :key="value.title"
                         class="p-6 bg-white rounded-2xl shadow-card hover:shadow-glow-yellow transition-all duration-300 transform hover:-translate-y-2">
                        <div class="text-4xl mb-4">{{ value.icon }}</div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">{{ value.title }}</h3>
                        <p class="text-gray-600 leading-relaxed">{{ value.description }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Team -->
        <section class="px-6 py-20 lg:px-8">
            <div class="mx-auto max-w-7xl">
                <div class="text-center mb-16">
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                        Meet Our Team
                    </h2>
                    <p class="mx-auto max-w-2xl text-xl text-gray-600">
                        The brilliant minds behind our AI platform, bringing decades of experience
                        from top tech companies and research institutions.
                    </p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div v-for="member in team" :key="member.name"
                         class="group p-6 bg-white rounded-2xl shadow-card hover:shadow-glow-yellow transition-all duration-300 transform hover:-translate-y-2">
                        <div class="text-center">
                            <div class="text-6xl mb-4 group-hover:animate-bounce-subtle">{{ member.avatar }}</div>
                            <h3 class="text-xl font-bold text-gray-900 mb-1">{{ member.name }}</h3>
                            <div class="text-primary-600 font-medium mb-4">{{ member.role }}</div>
                            <p class="text-gray-600 text-sm leading-relaxed mb-4">{{ member.bio }}</p>

                            <div class="flex justify-center space-x-3">
                                <a :href="member.social.linkedin"
                                   class="w-8 h-8 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center hover:bg-primary-200 transition-colors">
                                    <span class="text-sm">in</span>
                                </a>
                                <a :href="member.social.twitter"
                                   class="w-8 h-8 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center hover:bg-primary-200 transition-colors">
                                    <span class="text-sm">𝕏</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="px-6 py-20 bg-gradient-to-r from-primary-500 to-secondary-400">
            <div class="mx-auto max-w-4xl text-center">
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                    Join Our Mission
                </h2>
                <p class="text-xl text-primary-100 mb-8">
                    Be part of the AI revolution. Whether you're a customer, partner, or team member,
                    we'd love to have you on this incredible journey.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <Link :href="route('register')"
                          class="px-8 py-4 bg-white text-primary-600 text-lg font-semibold rounded-2xl hover:bg-primary-50 transition-all duration-300 shadow-lg">
                        Start Your Journey
                    </Link>
                    <button class="px-8 py-4 border-2 border-white text-white text-lg font-semibold rounded-2xl hover:bg-white/10 transition-all duration-300">
                        Join Our Team
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
                        © 2025 AI Project. Building the future, one algorithm at a time.
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>
