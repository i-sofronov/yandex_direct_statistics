<script setup lang="ts">

</script>

<template>
    <div class="module-breadcrumbs">
        <slot></slot>
    </div>
</template>

<style lang="scss">
.module-breadcrumbs{
    display: flex;
    gap: 2px;
    align-items: start;

    a{
        transition: all .3s ease-out;
        color:#8d8d8d;

        &:hover{
            color:#151515;
            text-decoration: underline;
        }

        &:not(:last-child)::after{
            content: "/";
            margin-left: 2px;
            font-size: 14px;
            display: inline-block;
            color:#8d8d8d;
        }

        &.--back{
            margin-right: 12px;

            &::after{
                display: none;
            }
        }
    }
}
</style>
