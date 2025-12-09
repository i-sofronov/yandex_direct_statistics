<script setup lang="ts">
const props = defineProps([
    'contentTitle'
]);
</script>

<template>
<div class="module-table">
    <div class="module-table__content">
        <div class="module-table__head">
            <div v-if="contentTitle" class="module-table__content-title">
                {{ contentTitle }}
            </div>
            <slot name="module-table-head"></slot>
        </div>
        <div class="module-table__body">
            <slot name="module-table-body"></slot>
        </div>
        <div class="module-table__footer">
            <slot name="module-table-footer"></slot>
        </div>
    </div>
</div>
</template>

<style scoped lang="scss">
.module-table{
    &:not(:last-child){
        margin-bottom: 21px;
    }

    &__content{
        display: flex;
        flex-direction: column;

        &-title{
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 17px;
        }
    }

    &__head,
    &__body{
        display: flex;
        flex-direction: column;
    }
}
</style>
