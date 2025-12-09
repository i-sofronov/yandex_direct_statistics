<script setup lang="ts">
import AppDatePicker from '@/core/components/ui/datepicker/AppDatepicker.vue';

const props = defineProps(['startDate', 'endDate']);
const emits = defineEmits(['calendarRangeUpdated'])
</script>

<template>
    <div class="module-calendar">
        <slot>
            <AppDatePicker
                :initial-start-date="startDate"
                :initial-end-date="endDate"
                @updateRange="emits('calendarRangeUpdated', $event)"
            ></AppDatePicker>
        </slot>
    </div>
</template>

<style scoped lang="scss">
    .module-calendar{
        margin-left: auto!important;
        margin-right: unset!important;
        min-width: 110px;
        max-width: 120px;
    }
</style>
