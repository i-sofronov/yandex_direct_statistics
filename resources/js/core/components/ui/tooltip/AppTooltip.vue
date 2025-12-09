<template>
    <div @click="isOpen = !isOpen" class="tooltip">
        <slot></slot>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';

const isOpen = ref(false);
</script>

<style scoped>
    .tooltip{
        display: flex;
        flex-direction: column;
        gap: 10px;
        padding: 12px;
        border-radius: 6px;
        min-width: 140px;
        background-color: #fff;
        z-index: 9999;
    }
</style>
