<script setup>
import { Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';

defineProps({
    canLogin: {
        type: Boolean,
        default: false,
    },
    canRegister: {
        type: Boolean,
        default: false,
    },
    currentRoute: {
        type: String,
        default: 'home',
    },
});

const isMobileMenuOpen = ref(false);
const isScrolled = ref(false);

const toggleMobileMenu = () => {
    isMobileMenuOpen.value = !isMobileMenuOpen.value;
};

const handleScroll = () => {
    isScrolled.value = window.scrollY > 10;
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});

const navigationItems = [
    { name: 'Home', route: 'home', href: '/' },
    { name: 'Products', route: 'products', href: '/products' },
    { name: 'Features', route: 'features', href: '/features' },
    { name: 'About', route: 'about', href: '/about' },
    { name: 'Contact', route: 'contact', href: '/contact' },
];

// Business navigation items (when authenticated)
const businessItems = [
    { name: 'Dashboard', route: 'dashboard', href: '/dashboard' },
    { name: 'Orders', route: 'orders', href: '/orders' },
    { name: 'Invoices', route: 'invoices', href: '/invoices' },
    { name: 'Quotes', route: 'quotes', href: '/quotes' },
    { name: 'Customers', route: 'customers', href: '/customers' },
];
</script>

<template>
    <nav :class="[
        'fixed top-0 left-0 right-0 z-50 transition-all duration-300',
        isScrolled ? 'bg-white/95 backdrop-blur-lg border-b border-primary-100 shadow-lg' : 'bg-white/80 backdrop-blur-sm border-b border-primary-100'
    ]">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <Link href="/" class="flex items-center space-x-2 group">
                <div
                    class="h-10 w-10 rounded-xl bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center shadow-glow-yellow group-hover:shadow-glow-yellow-lg transition-all duration-300">
                    <span class="text-white font-bold text-lg">AI</span>
                </div>
                <span
                    class="text-xl font-bold text-gray-900 group-hover:text-primary-600 transition-colors duration-300">Project</span>
                </Link>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <!-- Public Navigation -->
                    <template v-if="!$page.props.auth?.user">
                        <Link v-for="item in navigationItems" :key="item.route" :href="item.href" :class="[
                            'text-gray-600 hover:text-primary-600 transition-colors duration-200 font-medium relative py-2',
                            currentRoute === item.route ? 'text-primary-600' : ''
                        ]">
                        {{ item.name }}
                        <span v-if="currentRoute === item.route"
                            class="absolute bottom-0 left-0 right-0 h-0.5 bg-primary-500 rounded-full"></span>
                        </Link>
                    </template>

                    <!-- Business Navigation (when authenticated) -->
                    <template v-else>
                        <Link v-for="item in businessItems" :key="item.route" :href="item.href" :class="[
                            'text-gray-600 hover:text-primary-600 transition-colors duration-200 font-medium relative py-2',
                            currentRoute === item.route ? 'text-primary-600' : ''
                        ]">
                        {{ item.name }}
                        <span v-if="currentRoute === item.route"
                            class="absolute bottom-0 left-0 right-0 h-0.5 bg-primary-500 rounded-full"></span>
                        </Link>
                    </template>
                </div>

                <!-- Desktop Auth Buttons -->
                <div v-if="canLogin" class="hidden md:flex items-center space-x-4">
                    <Link v-if="$page.props.auth?.user" :href="route('dashboard')"
                        class="rounded-lg px-6 py-2.5 bg-primary-500 text-white font-medium hover:bg-primary-600 transition-all duration-200 shadow-glow-yellow">
                    Dashboard
                    </Link>
                    <template v-else>
                        <Link :href="route('login')"
                            class="text-gray-700 font-medium hover:text-primary-600 transition-colors duration-200">
                        Log in
                        </Link>
                        <Link v-if="canRegister" :href="route('register')"
                            class="rounded-lg px-6 py-2.5 bg-primary-500 text-white font-medium hover:bg-primary-600 transition-all duration-200 shadow-glow-yellow">
                        Get Started
                        </Link>
                    </template>
                </div>

                <!-- Mobile menu button -->
                <button @click="toggleMobileMenu"
                    class="md:hidden p-2 rounded-lg text-gray-600 hover:text-primary-600 hover:bg-primary-50 transition-colors duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path v-if="!isMobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div v-if="isMobileMenuOpen" class="md:hidden bg-white border-b border-primary-100 shadow-lg">
            <div class="px-6 pt-2 pb-3 space-y-1">
                <!-- Mobile Public Navigation -->
                <template v-if="!$page.props.auth?.user">
                    <Link v-for="item in navigationItems" :key="item.route" :href="item.href" :class="[
                        'block px-3 py-2 rounded-md font-medium transition-colors duration-200',
                        currentRoute === item.route
                            ? 'text-primary-600 bg-primary-50'
                            : 'text-gray-700 hover:text-primary-600 hover:bg-primary-50'
                    ]">
                    {{ item.name }}
                    </Link>
                </template>

                <!-- Mobile Business Navigation -->
                <template v-else>
                    <Link v-for="item in businessItems" :key="item.route" :href="item.href" :class="[
                        'block px-3 py-2 rounded-md font-medium transition-colors duration-200',
                        currentRoute === item.route
                            ? 'text-primary-600 bg-primary-50'
                            : 'text-gray-700 hover:text-primary-600 hover:bg-primary-50'
                    ]">
                    {{ item.name }}
                    </Link>
                </template>

                <!-- Mobile Auth Buttons -->
                <div class="mt-4 space-y-2">
                    <template v-if="$page.props.auth?.user">
                        <Link :href="route('profile.edit')"
                            class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-primary-600 hover:bg-primary-50 transition-colors duration-200">
                        Profile
                        </Link>
                        <Link :href="route('logout')" method="post"
                            class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-primary-600 hover:bg-primary-50 transition-colors duration-200">
                        Logout
                        </Link>
                    </template>
                    <template v-else-if="canLogin">
                        <Link :href="route('login')"
                            class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-primary-600 hover:bg-primary-50 transition-colors duration-200">
                        Log in
                        </Link>
                        <Link v-if="canRegister" :href="route('register')"
                            class="block px-3 py-2 rounded-md text-base font-medium bg-primary-500 text-white hover:bg-primary-600 transition-colors duration-200">
                        Register
                        </Link>
                    </template>
                </div>
            </div>
        </div>
    </nav>
</template>
