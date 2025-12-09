<script setup lang="ts">
import { computed, inject, PropType, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import ModuleTableCell from '@/features/projects/components/layouts/module/ModuleTableCell.vue';
import AppTooltipItem from '@/core/components/ui/tooltip/AppTooltipItem.vue';
import AppTooltip from '@/core/components/ui/tooltip/AppTooltip.vue';
import TableIndicator from '@/features/projects/components/accounts/TableIndicator.vue';
import ModuleTableRow from '@/features/projects/components/layouts/module/ModuleTableRow.vue';
import DotsIcon from '@/core/components/ui/icons/DotsIcon.vue';
import { useTooltip } from '@/features/projects/composables/useTooltip';
import { EnterIcon } from '@/core/components/ui/icons';
import Account, { YandexDirectTotalInterface } from '@/features/projects/models/Account.model';

const props = defineProps({
    id: {
        type: Number,
        required: true
    },
    name: {
        type: String,
        required: true
    },
    metadata: {
        type: Object,
        required: true
    },
    total: {
        type: Object as PropType<YandexDirectTotalInterface>,
        required: true
    },
});

const tooltipId = computed(() => {
    return 'direct-table-row-tooltip-' + props.id
})

const menuButtonRef = ref<HTMLElement>();
const menuRef = ref<HTMLElement>();

const { isOpen, floatingStyles, toggle } = useTooltip(
    false,
    menuButtonRef,
    menuRef,
    tooltipId.value
)

const projectId = inject('projectId', 0)
</script>

<template>
    <ModuleTableRow>
        <ModuleTableCell cell-width="65">
            <div :id="tooltipId" style="width: 100%; cursor: pointer" @click="toggle" ref="menuButtonRef" class="module-opts">
                <DotsIcon style="pointer-events: none"></DotsIcon>
            </div>

            <AppTooltip
                v-show="isOpen"
                ref="menuRef"
                class="menu"
                :style="floatingStyles"
            >
                <AppTooltipItem :class="'--danger'">
                    <AppTooltipItem :class="'--danger'">
                        <Link
                            :href="`/accounts/${id}`"
                            method="delete"
                            class="btn btn-danger"
                            :style="{border: 0, padding: 0}"
                            preserve-scroll
                        >
                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M4.8125 4.8125C4.92853 4.8125 5.03981 4.85859 5.12186 4.94064C5.20391 5.02269 5.25 5.13397 5.25 5.25V10.5C5.25 10.616 5.20391 10.7273 5.12186 10.8094C5.03981 10.8914 4.92853 10.9375 4.8125 10.9375C4.69647 10.9375 4.58519 10.8914 4.50314 10.8094C4.42109 10.7273 4.375 10.616 4.375 10.5V5.25C4.375 5.13397 4.42109 5.02269 4.50314 4.94064C4.58519 4.85859 4.69647 4.8125 4.8125 4.8125ZM7 4.8125C7.11603 4.8125 7.22731 4.85859 7.30936 4.94064C7.39141 5.02269 7.4375 5.13397 7.4375 5.25V10.5C7.4375 10.616 7.39141 10.7273 7.30936 10.8094C7.22731 10.8914 7.11603 10.9375 7 10.9375C6.88397 10.9375 6.77269 10.8914 6.69064 10.8094C6.60859 10.7273 6.5625 10.616 6.5625 10.5V5.25C6.5625 5.13397 6.60859 5.02269 6.69064 4.94064C6.77269 4.85859 6.88397 4.8125 7 4.8125ZM9.625 5.25C9.625 5.13397 9.57891 5.02269 9.49686 4.94064C9.41481 4.85859 9.30353 4.8125 9.1875 4.8125C9.07147 4.8125 8.96019 4.85859 8.87814 4.94064C8.79609 5.02269 8.75 5.13397 8.75 5.25V10.5C8.75 10.616 8.79609 10.7273 8.87814 10.8094C8.96019 10.8914 9.07147 10.9375 9.1875 10.9375C9.30353 10.9375 9.41481 10.8914 9.49686 10.8094C9.57891 10.7273 9.625 10.616 9.625 10.5V5.25Z"
                                    fill="#EC4427" />
                                <path
                                    d="M12.6875 2.625C12.6875 2.85706 12.5953 3.07962 12.4312 3.24372C12.2671 3.40781 12.0446 3.5 11.8125 3.5H11.375V11.375C11.375 11.8391 11.1906 12.2842 10.8624 12.6124C10.5342 12.9406 10.0891 13.125 9.625 13.125H4.375C3.91087 13.125 3.46575 12.9406 3.13756 12.6124C2.80937 12.2842 2.625 11.8391 2.625 11.375V3.5H2.1875C1.95544 3.5 1.73288 3.40781 1.56878 3.24372C1.40469 3.07962 1.3125 2.85706 1.3125 2.625V1.75C1.3125 1.51794 1.40469 1.29538 1.56878 1.13128C1.73288 0.967187 1.95544 0.875 2.1875 0.875H5.25C5.25 0.642936 5.34219 0.420376 5.50628 0.256282C5.67038 0.0921872 5.89294 0 6.125 0L7.875 0C8.10706 0 8.32962 0.0921872 8.49372 0.256282C8.65781 0.420376 8.75 0.642936 8.75 0.875H11.8125C12.0446 0.875 12.2671 0.967187 12.4312 1.13128C12.5953 1.29538 12.6875 1.51794 12.6875 1.75V2.625ZM3.60325 3.5L3.5 3.55163V11.375C3.5 11.6071 3.59219 11.8296 3.75628 11.9937C3.92038 12.1578 4.14294 12.25 4.375 12.25H9.625C9.85706 12.25 10.0796 12.1578 10.2437 11.9937C10.4078 11.8296 10.5 11.6071 10.5 11.375V3.55163L10.3967 3.5H3.60325ZM2.1875 2.625H11.8125V1.75H2.1875V2.625Z"
                                    fill="#EC4427" />
                            </svg>

                            Удалить
                        </Link>
                    </AppTooltipItem>
                </AppTooltipItem>
            </AppTooltip>
        </ModuleTableCell>

        <ModuleTableCell cell-width="65">
            {{ id }}
        </ModuleTableCell>

        <ModuleTableCell cell-width="200">
            <div v-if="metadata.type === 'vk_group'">
                <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8.14583 17H8.85417C12.7075 17 14.6271 17 15.8171 15.81C17 14.62 17 12.6933 17 8.85417V8.13167C17 4.30667 17 2.38 15.8171 1.19C14.6271 0 12.7004 0 8.85417 0H8.14583C4.2925 0 2.37292 0 1.18292 1.19C0 2.38 0 4.30667 0 8.14583V8.86833C0 12.6933 0 14.62 1.19 15.81C2.38 17 4.30667 17 8.14583 17Z" fill="#0077FF"/>
                    <path d="M9.04556 12.2471C5.17098 12.2471 2.96098 9.5909 2.8689 5.1709H4.80973C4.87348 8.41507 6.30431 9.78923 7.43765 10.0726V5.1709H9.26515V7.96881C10.3843 7.8484 11.5601 6.5734 11.9568 5.1709H13.7843C13.6348 5.89827 13.3369 6.58698 12.9091 7.19395C12.4813 7.80091 11.9328 8.31307 11.2981 8.6984C12.0066 9.05048 12.6325 9.54882 13.1343 10.1605C13.6361 10.7723 14.0025 11.4834 14.2093 12.2471H12.1976C12.012 11.5838 11.6347 10.99 11.1131 10.5402C10.5914 10.0903 9.94858 9.80446 9.26515 9.7184V12.2471H9.04556Z" fill="white"/>
                </svg>
            </div>
            <div v-if="['instagram', 'instagram_new'].includes(metadata.type)">
                <svg width="17" height="17" viewBox="0 0 2500 2500" xmlns="http://www.w3.org/2000/svg"><defs><radialGradient id="0" cx="332.14" cy="2511.81" r="3263.54" gradientUnits="userSpaceOnUse"><stop offset=".09" stop-color="#fa8f21"/><stop offset=".78" stop-color="#d82d7e"/></radialGradient><radialGradient id="1" cx="1516.14" cy="2623.81" r="2572.12" gradientUnits="userSpaceOnUse"><stop offset=".64" stop-color="#8c3aaa" stop-opacity="0"/><stop offset="1" stop-color="#8c3aaa"/></radialGradient></defs><path d="M833.4,1250c0-230.11,186.49-416.7,416.6-416.7s416.7,186.59,416.7,416.7-186.59,416.7-416.7,416.7S833.4,1480.11,833.4,1250m-225.26,0c0,354.5,287.36,641.86,641.86,641.86S1891.86,1604.5,1891.86,1250,1604.5,608.14,1250,608.14,608.14,895.5,608.14,1250M1767.27,582.69a150,150,0,1,0,150.06-149.94h-0.06a150.07,150.07,0,0,0-150,149.94M745,2267.47c-121.87-5.55-188.11-25.85-232.13-43-58.36-22.72-100-49.78-143.78-93.5s-70.88-85.32-93.5-143.68c-17.16-44-37.46-110.26-43-232.13-6.06-131.76-7.27-171.34-7.27-505.15s1.31-373.28,7.27-505.15c5.55-121.87,26-188,43-232.13,22.72-58.36,49.78-100,93.5-143.78s85.32-70.88,143.78-93.5c44-17.16,110.26-37.46,232.13-43,131.76-6.06,171.34-7.27,505-7.27s373.28,1.31,505.15,7.27c121.87,5.55,188,26,232.13,43,58.36,22.62,100,49.78,143.78,93.5s70.78,85.42,93.5,143.78c17.16,44,37.46,110.26,43,232.13,6.06,131.87,7.27,171.34,7.27,505.15s-1.21,373.28-7.27,505.15c-5.55,121.87-25.95,188.11-43,232.13-22.72,58.36-49.78,100-93.5,143.68s-85.42,70.78-143.78,93.5c-44,17.16-110.26,37.46-232.13,43-131.76,6.06-171.34,7.27-505.15,7.27s-373.28-1.21-505-7.27M734.65,7.57c-133.07,6.06-224,27.16-303.41,58.06C349,97.54,279.38,140.35,209.81,209.81S97.54,349,65.63,431.24c-30.9,79.46-52,170.34-58.06,303.41C1.41,867.93,0,910.54,0,1250s1.41,382.07,7.57,515.35c6.06,133.08,27.16,223.95,58.06,303.41,31.91,82.19,74.62,152,144.18,221.43S349,2402.37,431.24,2434.37c79.56,30.9,170.34,52,303.41,58.06C868,2498.49,910.54,2500,1250,2500s382.07-1.41,515.35-7.57c133.08-6.06,223.95-27.16,303.41-58.06,82.19-32,151.86-74.72,221.43-144.18s112.18-139.24,144.18-221.43c30.9-79.46,52.1-170.34,58.06-303.41,6.06-133.38,7.47-175.89,7.47-515.35s-1.41-382.07-7.47-515.35c-6.06-133.08-27.16-224-58.06-303.41-32-82.19-74.72-151.86-144.18-221.43S2150.95,97.54,2068.86,65.63c-79.56-30.9-170.44-52.1-303.41-58.06C1632.17,1.51,1589.56,0,1250.1,0S868,1.41,734.65,7.57" fill="url(#0)"/><path d="M833.4,1250c0-230.11,186.49-416.7,416.6-416.7s416.7,186.59,416.7,416.7-186.59,416.7-416.7,416.7S833.4,1480.11,833.4,1250m-225.26,0c0,354.5,287.36,641.86,641.86,641.86S1891.86,1604.5,1891.86,1250,1604.5,608.14,1250,608.14,608.14,895.5,608.14,1250M1767.27,582.69a150,150,0,1,0,150.06-149.94h-0.06a150.07,150.07,0,0,0-150,149.94M745,2267.47c-121.87-5.55-188.11-25.85-232.13-43-58.36-22.72-100-49.78-143.78-93.5s-70.88-85.32-93.5-143.68c-17.16-44-37.46-110.26-43-232.13-6.06-131.76-7.27-171.34-7.27-505.15s1.31-373.28,7.27-505.15c5.55-121.87,26-188,43-232.13,22.72-58.36,49.78-100,93.5-143.78s85.32-70.88,143.78-93.5c44-17.16,110.26-37.46,232.13-43,131.76-6.06,171.34-7.27,505-7.27s373.28,1.31,505.15,7.27c121.87,5.55,188,26,232.13,43,58.36,22.62,100,49.78,143.78,93.5s70.78,85.42,93.5,143.78c17.16,44,37.46,110.26,43,232.13,6.06,131.87,7.27,171.34,7.27,505.15s-1.21,373.28-7.27,505.15c-5.55,121.87-25.95,188.11-43,232.13-22.72,58.36-49.78,100-93.5,143.68s-85.42,70.78-143.78,93.5c-44,17.16-110.26,37.46-232.13,43-131.76,6.06-171.34,7.27-505.15,7.27s-373.28-1.21-505-7.27M734.65,7.57c-133.07,6.06-224,27.16-303.41,58.06C349,97.54,279.38,140.35,209.81,209.81S97.54,349,65.63,431.24c-30.9,79.46-52,170.34-58.06,303.41C1.41,867.93,0,910.54,0,1250s1.41,382.07,7.57,515.35c6.06,133.08,27.16,223.95,58.06,303.41,31.91,82.19,74.62,152,144.18,221.43S349,2402.37,431.24,2434.37c79.56,30.9,170.34,52,303.41,58.06C868,2498.49,910.54,2500,1250,2500s382.07-1.41,515.35-7.57c133.08-6.06,223.95-27.16,303.41-58.06,82.19-32,151.86-74.72,221.43-144.18s112.18-139.24,144.18-221.43c30.9-79.46,52.1-170.34,58.06-303.41,6.06-133.38,7.47-175.89,7.47-515.35s-1.41-382.07-7.47-515.35c-6.06-133.08-27.16-224-58.06-303.41-32-82.19-74.72-151.86-144.18-221.43S2150.95,97.54,2068.86,65.63c-79.56-30.9-170.44-52.1-303.41-58.06C1632.17,1.51,1589.56,0,1250.1,0S868,1.41,734.65,7.57" fill="url(#1)"/></svg>
            </div>
            <div v-if="metadata.type === 'telegram'">
                <svg width="17" height="17" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="16" cy="16" r="14" fill="url(#paint0_linear_87_7225)"/>
                    <path d="M22.9866 10.2088C23.1112 9.40332 22.3454 8.76755 21.6292 9.082L7.36482 15.3448C6.85123 15.5703 6.8888 16.3483 7.42147 16.5179L10.3631 17.4547C10.9246 17.6335 11.5325 17.541 12.0228 17.2023L18.655 12.6203C18.855 12.4821 19.073 12.7665 18.9021 12.9426L14.1281 17.8646C13.665 18.3421 13.7569 19.1512 14.314 19.5005L19.659 22.8523C20.2585 23.2282 21.0297 22.8506 21.1418 22.1261L22.9866 10.2088Z" fill="white"/>
                    <defs>
                        <linearGradient id="paint0_linear_87_7225" x1="16" y1="2" x2="16" y2="30" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#37BBFE"/>
                            <stop offset="1" stop-color="#007DBB"/>
                        </linearGradient>
                    </defs>
                </svg>
            </div>

            {{ name }}
        </ModuleTableCell>

        <ModuleTableCell cell-width="125">
            <TableIndicator
                :indicator="total?.followers"
                :dynamic="total.dynamic.followers"
            ></TableIndicator>
        </ModuleTableCell>

        <ModuleTableCell cell-width="100">
            <TableIndicator
                :indicator="total.views"
                :dynamic="total.dynamic.views"
            ></TableIndicator>
        </ModuleTableCell>

        <ModuleTableCell cell-width="100">
            <TableIndicator
                :indicator="total.likes"
                :dynamic="total.dynamic.likes"
            ></TableIndicator>
        </ModuleTableCell>

        <ModuleTableCell cell-width="100">
            <TableIndicator
                :indicator="total.comments"
                :dynamic="total.dynamic.comments"
            ></TableIndicator>
        </ModuleTableCell>

        <ModuleTableCell cell-width="75">
            <TableIndicator
                :indicator="total.posts"
                :dynamic="total.dynamic.posts"
            ></TableIndicator>
        </ModuleTableCell>

        <ModuleTableCell cell-width="75">
            <TableIndicator
                :indicator="total.reposts"
                :dynamic="total.dynamic.reposts"
            ></TableIndicator>
        </ModuleTableCell>

        <ModuleTableCell cell-width="85">
            <TableIndicator
                :indicator="total.er.toFixed(2)"
                :dynamic="total.dynamic.er.toFixed(2)"
            ></TableIndicator>
        </ModuleTableCell>

        <ModuleTableCell cell-width="65" style="margin-left: auto">
            <Link :href="'/projects/' + projectId + '/accounts/' + id">
                <EnterIcon></EnterIcon>
            </Link>
        </ModuleTableCell>
    </ModuleTableRow>
</template>

<style scoped lang="scss">

</style>
