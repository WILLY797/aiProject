<template>
    <form @submit.prevent="submit">
        <input v-model="input" placeholder="Type something..." />
        <button type="submit">Send</button>
        <div v-if="output">Response: {{ output }}</div>
    </form>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            input: '',
            output: '',
        };
    },
    methods: {
        async submit() {
            try {
                const res = await axios.post('/api/ai/process', { input: this.input });
                this.output = res.data.output;
            } catch (err) {
                console.error(err);
            }
        }
    }
};
</script>
