<script setup lang="ts">

import FilterIcon from '@/core/components/ui/icons/FilterIcon.vue';

const emit = defineEmits(['toggleModuleFilter']);
</script>

<template>
    <FilterIcon :class="'module-filter'" @click="$emit('toggleModuleFilter')"></FilterIcon>
</template>

<style scoped lang="scss">

</style>
