<template>
    <div class="counter-container">
        <h2>{{ $t('counter.title') }}</h2>
        <p>{{ $t('counter.text', { count: counter }) }}</p>
        <button @click="incrementCounter">{{ $t('counter.button') }}</button>
    </div>
</template>

<script>
export default {
    name: 'LicznikKlikniec',
    data() {
        return {
            counter: 0
        }
    },
    methods: {
        incrementCounter() {
            this.counter += 1
        }
    }
}
</script>

<style scoped>
.counter-container {
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    max-width: 300px;
    margin: 0 auto;
    text-align: center;
}

button {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    margin-top: 10px;
}

button:hover {
    background-color: #45a049;
}
</style>
