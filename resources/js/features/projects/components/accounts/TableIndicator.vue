<script setup lang="ts">
 defineProps(['indicator', 'dynamic'])
</script>

<template>
    <div class="account-digit">
        {{ indicator }}
        <span
            :class="['account-digit__dynamic', (dynamic > 0 ? '--positive' : '--negative')]">
            {{ dynamic }}
        </span>
    </div>
</template>

<style scoped lang="scss">
.account-digit{
    &__dynamic{
        font-size: 10px;

        &.--positive{
            color:#36CE68;
        }
        &.--negative{
            color:#EC4427;
        }
    }
}
</style>
