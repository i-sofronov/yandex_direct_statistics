<template>
    <a :href="href" @click="$emit('click', eventName)" class="tooltip-item">
        <slot></slot>
    </a>
</template>

<script>
export default {
    name: 'AppTooltipItem',
    props: ['eventName', 'href']
};
</script>

<style scoped lang="scss">
    .tooltip-item{
        display: flex;
        gap: 6px;
        align-items: center;
        font-size: 12px;
        font-weight: 300;
        color:#545454;
        cursor: pointer;

        &.--danger {
            color:#EC4427
        }
    }
</style>
