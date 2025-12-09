<script setup lang="ts">
 const props = defineProps({
     title:{
         type: String,
         required: true
     }
 })
</script>

<template>
    <div class="module-title">
        {{ title }}
    </div>
</template>

<style scoped lang="scss">
.module-title{
    font-size: 24px;
    font-weight: 550;
}
</style>
