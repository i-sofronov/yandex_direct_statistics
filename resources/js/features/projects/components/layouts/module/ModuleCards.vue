<script setup lang="ts">

</script>

<template>
    <div class="module-cards">
        <slot></slot>
    </div>
</template>

<style scoped lang="scss">
.module-cards{
    display: flex;
    gap: 25px;
    flex-wrap: wrap;

    & > * {
        width: calc(100% / 2 - 12.5px);
    }
}
</style>
