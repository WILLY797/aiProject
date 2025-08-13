<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    name: '',
    email: '',
    company: '',
    subject: '',
    message: '',
    interest: ''
});

const interests = ref([
    'General Inquiry',
    'Product Demo',
    'Enterprise Sales',
    'Technical Support',
    'Partnership',
    'Press/Media'
]);

const contactInfo = ref([
    {
        icon: '📧',
        title: 'Email',
        content: 'hello@aiproject.com',
        description: 'Send us an email anytime'
    },
    {
        icon: '📞',
        title: 'Phone',
        content: '+1 (555) 123-4567',
        description: 'Mon-Fri from 8am to 6pm PST'
    },
    {
        icon: '📍',
        title: 'Office',
        content: 'San Francisco, CA',
        description: '123 Innovation Drive, Suite 100'
    },
    {
        icon: '💬',
        title: 'Live Chat',
        content: 'Available 24/7',
        description: 'Get instant help from our team'
    }
]);

const submitForm = () => {
    form.post('/contact', {
        onSuccess: () => {
            form.reset();
            // You could show a success message here
        }
    });
};
</script>

<template>
    <Head title="Contact Us - AI Project" />

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
                        <Link href="/about" class="text-gray-600 hover:text-primary-600 transition-colors">About</Link>
                        <Link href="/contact" class="text-primary-600 font-medium">Contact</Link>
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
                    Get in
                    <span class="bg-gradient-to-r from-primary-500 to-secondary-400 bg-clip-text text-transparent">
                        Touch
                    </span>
                </h1>
                <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                    Have questions about our AI platform? Want to schedule a demo?
                    Our team is here to help you succeed.
                </p>
            </div>
        </section>

        <!-- Contact Methods -->
        <section class="px-6 py-16">
            <div class="mx-auto max-w-7xl">
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div v-for="info in contactInfo" :key="info.title"
                         class="text-center p-6 bg-white rounded-2xl shadow-card hover:shadow-glow-yellow transition-all duration-300 transform hover:-translate-y-2">
                        <div class="text-4xl mb-4">{{ info.icon }}</div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ info.title }}</h3>
                        <div class="text-primary-600 font-semibold mb-2">{{ info.content }}</div>
                        <p class="text-sm text-gray-600">{{ info.description }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Form -->
        <section class="px-6 py-20 lg:px-8">
            <div class="mx-auto max-w-7xl">
                <div class="grid lg:grid-cols-2 gap-12">
                    <!-- Form -->
                    <div class="bg-white rounded-2xl p-8 shadow-glow-yellow">
                        <h2 class="text-3xl font-bold text-gray-900 mb-6">Send us a message</h2>

                        <form @submit.prevent="submitForm" class="space-y-6">
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Full Name
                                    </label>
                                    <input v-model="form.name"
                                           type="text"
                                           id="name"
                                           required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
                                           placeholder="John Doe">
                                    <div v-if="form.errors.name" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.name }}
                                    </div>
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                        Email Address
                                    </label>
                                    <input v-model="form.email"
                                           type="email"
                                           id="email"
                                           required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
                                           placeholder="john@example.com">
                                    <div v-if="form.errors.email" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.email }}
                                    </div>
                                </div>
                            </div>

                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label for="company" class="block text-sm font-medium text-gray-700 mb-2">
                                        Company (Optional)
                                    </label>
                                    <input v-model="form.company"
                                           type="text"
                                           id="company"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
                                           placeholder="Acme Corp">
                                </div>

                                <div>
                                    <label for="interest" class="block text-sm font-medium text-gray-700 mb-2">
                                        I'm interested in
                                    </label>
                                    <select v-model="form.interest"
                                            id="interest"
                                            required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200">
                                        <option value="">Select an option</option>
                                        <option v-for="interest in interests" :key="interest" :value="interest">
                                            {{ interest }}
                                        </option>
                                    </select>
                                    <div v-if="form.errors.interest" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.interest }}
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                                    Subject
                                </label>
                                <input v-model="form.subject"
                                       type="text"
                                       id="subject"
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
                                       placeholder="How can we help you?">
                                <div v-if="form.errors.subject" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.subject }}
                                </div>
                            </div>

                            <div>
                                <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                                    Message
                                </label>
                                <textarea v-model="form.message"
                                          id="message"
                                          rows="5"
                                          required
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200 resize-none"
                                          placeholder="Tell us more about your needs..."></textarea>
                                <div v-if="form.errors.message" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.message }}
                                </div>
                            </div>

                            <button type="submit"
                                    :disabled="form.processing"
                                    class="w-full py-4 bg-gradient-to-r from-primary-500 to-primary-600 text-white font-semibold rounded-lg hover:from-primary-600 hover:to-primary-700 transition-all duration-300 shadow-glow-yellow disabled:opacity-50 disabled:cursor-not-allowed">
                                <span v-if="form.processing">Sending...</span>
                                <span v-else>Send Message</span>
                            </button>
                        </form>
                    </div>

                    <!-- Additional Info -->
                    <div class="space-y-8">
                        <div class="bg-gradient-to-br from-primary-100 to-secondary-100 rounded-2xl p-8">
                            <h3 class="text-2xl font-bold text-gray-900 mb-4">
                                Why Choose Our AI Platform?
                            </h3>
                            <ul class="space-y-3">
                                <li class="flex items-start">
                                    <span class="w-6 h-6 bg-primary-500 text-white rounded-full flex items-center justify-center text-xs mr-3 mt-1">✓</span>
                                    <span class="text-gray-700">Enterprise-grade security and compliance</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="w-6 h-6 bg-primary-500 text-white rounded-full flex items-center justify-center text-xs mr-3 mt-1">✓</span>
                                    <span class="text-gray-700">24/7 expert support and consultation</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="w-6 h-6 bg-primary-500 text-white rounded-full flex items-center justify-center text-xs mr-3 mt-1">✓</span>
                                    <span class="text-gray-700">Custom AI solutions for your business</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="w-6 h-6 bg-primary-500 text-white rounded-full flex items-center justify-center text-xs mr-3 mt-1">✓</span>
                                    <span class="text-gray-700">Seamless integration with existing systems</span>
                                </li>
                            </ul>
                        </div>

                        <div class="bg-white rounded-2xl p-8 shadow-card">
                            <h3 class="text-xl font-bold text-gray-900 mb-4">
                                Schedule a Demo
                            </h3>
                            <p class="text-gray-600 mb-6">
                                See our AI platform in action with a personalized demo tailored to your business needs.
                            </p>
                            <button class="w-full py-3 border-2 border-primary-500 text-primary-600 font-semibold rounded-lg hover:bg-primary-50 transition-all duration-300">
                                Book Demo Call
                            </button>
                        </div>

                        <div class="bg-white rounded-2xl p-8 shadow-card">
                            <h3 class="text-xl font-bold text-gray-900 mb-4">
                                Emergency Support
                            </h3>
                            <p class="text-gray-600 mb-4">
                                For urgent technical issues, our emergency support team is available 24/7.
                            </p>
                            <div class="text-lg font-semibold text-primary-600">
                                📞 +1 (555) 911-HELP
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section class="px-6 py-20 bg-white/50 backdrop-blur-sm">
            <div class="mx-auto max-w-4xl">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                        Frequently Asked Questions
                    </h2>
                    <p class="text-lg text-gray-600">
                        Quick answers to common questions about our AI platform.
                    </p>
                </div>

                <div class="space-y-6">
                    <div class="bg-white rounded-lg p-6 shadow-card">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            How quickly can I get started?
                        </h3>
                        <p class="text-gray-600">
                            You can start using our platform immediately after signing up. Most customers are up and running within minutes with our guided onboarding process.
                        </p>
                    </div>

                    <div class="bg-white rounded-lg p-6 shadow-card">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            Do you offer custom integrations?
                        </h3>
                        <p class="text-gray-600">
                            Yes! We offer custom integrations and can work with your existing systems. Our team will help design a solution that fits your specific needs.
                        </p>
                    </div>

                    <div class="bg-white rounded-lg p-6 shadow-card">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            What kind of support do you provide?
                        </h3>
                        <p class="text-gray-600">
                            We provide 24/7 technical support, dedicated account management for enterprise customers, and comprehensive documentation and training resources.
                        </p>
                    </div>
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
                        © 2025 AI Project. We're here to help you succeed.
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>
