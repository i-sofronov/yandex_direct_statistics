<script setup lang="ts">
    defineProps(['cellWidth'])
</script>

<template>
    <div class="module-table__cell" :style="{width: `${cellWidth}px`}">
        <slot></slot>
    </div>
</template>

<style scoped lang="scss">
.module-table{
    &__cell{
        padding: 10px;
        color:#8d8d8d;
        text-align: center;
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
        gap: 5px;
    }
}
</style>
