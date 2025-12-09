<template>
    <div class="custom-datepicker">
        <VueDatePicker
            v-model="dateRange"
            range
            locale="ru"
            :format="format"
            :enable-time-picker="false"
            :clearable="false"
            :auto-apply="true"
            :preview-format="previewFormat"
            :input-class-name="inputClass"
            :calendar-class-name="calendarClass"
            :dp-class="dpClass"
            :min-date="minDate"
            :max-date="maxDate"
            :year-range="yearRange"
            :partial-range="false"
            :show-offset-dates="true"
            :position="'right'"
            @update:model-value="handleDateChange"
            @open="onPickerOpen"
            @close="onPickerClose"
        />
    </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import VueDatePicker from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'

const emit = defineEmits(['updateRange'])

const props = defineProps({
    initialStartDate: String,
    initialEndDate: String
})

const dateRange = ref([])
const isOpen = ref(false)

const format = 'dd.MM'
const previewFormat = 'dd MMM yyyy'

const inputClass = computed(() => [
    'custom-date-input',
    isOpen.value ? 'input-open' : ''
])

const calendarClass = 'custom-calendar'
const dpClass = 'custom-datepicker-container'

const minDate = new Date(2000, 0, 1)
const maxDate = new Date()
const yearRange = [2000, new Date().getFullYear()]

const setDefaultDateRange = () => {
    const today = new Date()
    const yesterday = new Date(today)
    yesterday.setDate(today.getDate() - 1)

    dateRange.value = [yesterday, today]
}

const setDateRangeFromProps = () => {
    if (props.initialStartDate && props.initialEndDate) {
        dateRange.value = [
            new Date(props.initialStartDate),
            new Date(props.initialEndDate)
        ]
    } else {
        setDefaultDateRange()
    }
}

const handleDateChange = (dates) => {
    if (dates && dates.length === 2) {
        const [start, end] = dates

        const startDate = start.toISOString().split('T')[0]
        const endDate = end.toISOString().split('T')[0]

        emit('updateRange', {startDate, endDate})
    }
}

const onPickerOpen = () => {
    isOpen.value = true
}

const onPickerClose = () => {
    isOpen.value = false
}

watch(() => [props.initialStartDate, props.initialEndDate], () => {
    setDateRangeFromProps()
})

onMounted(() => {
    setDateRangeFromProps()
})
</script>

<style scoped>
.custom-datepicker {
    position: relative;
}

:deep(.dp__input) {
    padding: 5px 8px;
    border: 1px solid #151515;
    border-radius: 8px;
    font-size: 12px;
    line-height: 1;
    transition: all 0.3s ease;
    background: white;
    cursor: pointer;
    font-family: Inter, 'sans-serif';
    color: #151515;
}

:deep(.dp__input::placeholder) {
    color: #151515;
}

:deep(.dp__input:hover) {
    border-color: #409eff;
}

:deep(.dp__input.input-open) {
    border-color: #409eff;
    box-shadow: 0 0 0 3px rgba(64, 158, 255, 0.1);
}

:deep(.dp__input_icon) {
    padding: 0;
    margin: 0;
    right:8px;
    color:#151515;
    left: unset;
}

:deep(.custom-calendar) {
    border: 1px solid #e1e5e9;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    overflow: hidden;
}

:deep(.custom-calendar .dp__menu) {
    border: none;
}

:deep(.custom-calendar .dp__calendar_header) {
    padding: 16px;
    background: #f8f9fa;
}

:deep(.custom-calendar .dp__calendar_header_item) {
    color: #666;
    font-weight: 500;
}

:deep(.custom-calendar .dp__cell_inner) {
    border-radius: 6px;
}

:deep(.custom-calendar .dp__range_between) {
    background: #ecf5ff;
    border: none;
}

:deep(.custom-calendar .dp__range_start),
:deep(.custom-calendar .dp__range_end) {
    background: #409eff;
    color: white;
}

:deep(.custom-calendar .dp__active_date) {
    background: #409eff;
    color: white;
}

:deep(.custom-calendar .dp__today) {
    border: 1px solid #409eff;
    color: #409eff;
}

:deep(.custom-datepicker-container) {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

:deep(.custom-calendar .dp__button) {
    border-radius: 6px;
}

:deep(.custom-calendar .dp__button:hover) {
    background: #f5f7fa;
}
</style>
