<script setup lang="ts">
</script>

<template>
    <div class="module-btns">
        <slot></slot>
    </div>
</template>

<style scoped lang="scss">
.module-btns{
    margin-left: 15px;
    display: flex;
    flex-wrap: wrap;
    width: fit-content;
    gap: 12px;
}
</style>
