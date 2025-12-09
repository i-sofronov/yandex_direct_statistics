<script setup lang="ts">
import { computed, useSlots } from 'vue';

const slots = useSlots()

const hasModuleHeadBottom = computed(() => slots['module-head-bottom']().length !== 0)

</script>

<template>
    <div class="module-layout module">
        <div class="module__content">
            <div class="module__head">
                <div class="module__head-top">
                    <slot name="module-head-top"></slot>
                </div>
                <div class="module__head-middle">
                    <slot name="module-head-middle"></slot>
                </div>
                <div class="module__head-bottom" v-if="hasModuleHeadBottom">
                    <slot name="module-head-bottom"></slot>
                </div>
            </div>
            <div class="module__body _scrollable" scroll-region>
                <slot name="module-body"></slot>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.module{
    &__content{
        display: grid;
        flex-direction: column;
        gap: 19px;
        grid-template-rows: minmax(68px, 1fr) calc(100vh - 267px) auto;
        grid-template-columns: auto;
    }

    &__head{
        display: flex;
        flex-direction: column;
        gap: 21px;

        &-top,
        &-middle,
        &-bottom{
            display: flex;
            align-items: center;
        }

        &-middle{
            & > *:nth-child(1){
                margin-right: 0;
            }
            & > *:nth-child(2){
                margin-right: auto;
                margin-left: 15px;
            }
        }

        &-bottom{
            width: 100%;
            gap: 17px;
        }
    }

    &__body{
        padding-top: 21px;
        border-top: 1px solid rgba(21,21,21,.08);
        overflow: auto;
        scrollbar-gutter: stable;
        position: relative;
    }
}
</style>
