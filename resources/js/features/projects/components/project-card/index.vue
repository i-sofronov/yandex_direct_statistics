<script setup lang="ts">
import DotsIcon from '@/core/components/ui/icons/DotsIcon.vue';
import { computed, defineProps, PropType, provide, ref, watch } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppTooltip from '@/core/components/ui/tooltip/AppTooltip.vue'
import AppTooltipItem from '@/core/components/ui/tooltip/AppTooltipItem.vue'
import ProjectApi from '@/features/projects/services/project.routing';
import UpdateProject from '@/features/projects/components/modal/UpdateProject.vue';
import { Project } from '@/features/projects/models';
import { useTooltip } from '@/features/projects/composables/useTooltip';
import ProjectStatistics from "./ProjectStatistics.vue"

const props = defineProps({
    project: {
        type: Object as PropType<Project>,
        required: true
    },
    updatedProjectId: {
        type: Number,
        required:false,
        default: null
    }
})

const updateModal = ref<{ open: () => void; close: () => void }>();

const tooltipId = computed(() => {
    return 'projects-card-tooltip-' + props.project.id
})

const menuButtonRef = ref<HTMLElement>();
const menuRef = ref<HTMLElement>();

const { isOpen, floatingStyles, toggle } = useTooltip(
    false,
    menuButtonRef,
    menuRef,
    tooltipId.value
)

watch(() => props.updatedProjectId, () => {
    if(props.updatedProjectId && Number(props.updatedProjectId) === props.project.id){
        updateModal.value?.open()
    }
})

async function getProjects(event: {
    startDate: string,
    endDate: string
} | null = null){
    const filterData = {
        start_date: '',
        end_date: '',
        search: ''
    }

    if(event?.startDate){
        filterData.start_date = event.startDate
    }

    if(event?.endDate){
        filterData.end_date = event.endDate
    }

    await ProjectApi.getProjects(filterData, false, true, ['initialProjects']);
}


provide('projectId', props.project.id)
</script>

<template>
    <div class="project-card">
        <div class="project-card__content">
            <div class="project-card__head">
                <div class="project-card__title">
                    {{ project.name }}
                </div>
                <div class="project-card__opts">
                    <div :id="tooltipId" @click="toggle" ref="menuButtonRef" class="project-card__opts" style="width: 24px; cursor: pointer; display: flex; justify-content: center">
                        <DotsIcon style="pointer-events: none"></DotsIcon>
                    </div>

                    <AppTooltip
                        v-if="isOpen"
                        ref="menuRef"
                        class="menu"
                        :style="floatingStyles"
                    >
                        <AppTooltipItem @click="updateModal && (function(){updateModal.open(); isOpen = false})()">
                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_184_3758)">
                                    <path d="M10.6278 0.127556C10.6685 0.0868127 10.7167 0.0544877 10.7699 0.032432C10.823 0.0103764 10.88 -0.000976563 10.9376 -0.000976562C10.9951 -0.000976562 11.0521 0.0103764 11.1053 0.032432C11.1584 0.0544877 11.2067 0.0868127 11.2473 0.127556L13.8723 2.75256C13.9131 2.7932 13.9454 2.84147 13.9674 2.89463C13.9895 2.94778 14.0009 3.00476 14.0009 3.06231C14.0009 3.11985 13.9895 3.17683 13.9674 3.22999C13.9454 3.28314 13.9131 3.33142 13.8723 3.37206L5.12232 12.1221C5.08034 12.1638 5.03033 12.1965 4.97532 12.2183L0.600325 13.9683C0.520819 14.0001 0.433721 14.0079 0.349829 13.9907C0.265937 13.9735 0.188941 13.9321 0.128385 13.8715C0.0678294 13.8109 0.0263776 13.7339 0.00916858 13.6501C-0.00804044 13.5662 -0.000249879 13.4791 0.0315745 13.3996L1.78157 9.02456C1.80339 8.96955 1.83613 8.91954 1.87782 8.87756L10.6278 0.127556ZM9.8062 2.18731L11.8126 4.19368L12.944 3.06231L10.9376 1.05593L9.8062 2.18731ZM11.194 4.81231L9.18758 2.80593L3.50007 8.49343V8.74981H3.93757C4.05361 8.74981 4.16489 8.7959 4.24693 8.87795C4.32898 8.95999 4.37507 9.07127 4.37507 9.18731V9.62481H4.81257C4.92861 9.62481 5.03989 9.6709 5.12193 9.75295C5.20398 9.83499 5.25007 9.94627 5.25007 10.0623V10.4998H5.50645L11.194 4.81231ZM2.65307 9.34043L2.56032 9.43318L1.22332 12.7766L4.5667 11.4396L4.65945 11.3468C4.57599 11.3156 4.50404 11.2597 4.45323 11.1865C4.40241 11.1133 4.37514 11.0264 4.37507 10.9373V10.4998H3.93757C3.82154 10.4998 3.71026 10.4537 3.62822 10.3717C3.54617 10.2896 3.50007 10.1783 3.50007 10.0623V9.62481H3.06257C2.97348 9.62474 2.88653 9.59747 2.81335 9.54666C2.74018 9.49584 2.68426 9.42389 2.65307 9.34043Z" fill="#545454"/>
                                </g>
                                <defs>
                                    <clipPath id="clip0_184_3758">
                                        <rect width="14" height="14" fill="white"/>
                                    </clipPath>
                                </defs>
                            </svg>

                            Редактировать
                        </AppTooltipItem>
                        <AppTooltipItem :class="'--danger'">
                            <Link
                                :href="`/projects/${project.id}`"
                                method="delete"
                                class="btn btn-danger"
                                :style="{border: 0, padding: 0}"
                                preserve-scroll
                            >
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4.8125 4.8125C4.92853 4.8125 5.03981 4.85859 5.12186 4.94064C5.20391 5.02269 5.25 5.13397 5.25 5.25V10.5C5.25 10.616 5.20391 10.7273 5.12186 10.8094C5.03981 10.8914 4.92853 10.9375 4.8125 10.9375C4.69647 10.9375 4.58519 10.8914 4.50314 10.8094C4.42109 10.7273 4.375 10.616 4.375 10.5V5.25C4.375 5.13397 4.42109 5.02269 4.50314 4.94064C4.58519 4.85859 4.69647 4.8125 4.8125 4.8125ZM7 4.8125C7.11603 4.8125 7.22731 4.85859 7.30936 4.94064C7.39141 5.02269 7.4375 5.13397 7.4375 5.25V10.5C7.4375 10.616 7.39141 10.7273 7.30936 10.8094C7.22731 10.8914 7.11603 10.9375 7 10.9375C6.88397 10.9375 6.77269 10.8914 6.69064 10.8094C6.60859 10.7273 6.5625 10.616 6.5625 10.5V5.25C6.5625 5.13397 6.60859 5.02269 6.69064 4.94064C6.77269 4.85859 6.88397 4.8125 7 4.8125ZM9.625 5.25C9.625 5.13397 9.57891 5.02269 9.49686 4.94064C9.41481 4.85859 9.30353 4.8125 9.1875 4.8125C9.07147 4.8125 8.96019 4.85859 8.87814 4.94064C8.79609 5.02269 8.75 5.13397 8.75 5.25V10.5C8.75 10.616 8.79609 10.7273 8.87814 10.8094C8.96019 10.8914 9.07147 10.9375 9.1875 10.9375C9.30353 10.9375 9.41481 10.8914 9.49686 10.8094C9.57891 10.7273 9.625 10.616 9.625 10.5V5.25Z" fill="#EC4427"/>
                                    <path d="M12.6875 2.625C12.6875 2.85706 12.5953 3.07962 12.4312 3.24372C12.2671 3.40781 12.0446 3.5 11.8125 3.5H11.375V11.375C11.375 11.8391 11.1906 12.2842 10.8624 12.6124C10.5342 12.9406 10.0891 13.125 9.625 13.125H4.375C3.91087 13.125 3.46575 12.9406 3.13756 12.6124C2.80937 12.2842 2.625 11.8391 2.625 11.375V3.5H2.1875C1.95544 3.5 1.73288 3.40781 1.56878 3.24372C1.40469 3.07962 1.3125 2.85706 1.3125 2.625V1.75C1.3125 1.51794 1.40469 1.29538 1.56878 1.13128C1.73288 0.967187 1.95544 0.875 2.1875 0.875H5.25C5.25 0.642936 5.34219 0.420376 5.50628 0.256282C5.67038 0.0921872 5.89294 0 6.125 0L7.875 0C8.10706 0 8.32962 0.0921872 8.49372 0.256282C8.65781 0.420376 8.75 0.642936 8.75 0.875H11.8125C12.0446 0.875 12.2671 0.967187 12.4312 1.13128C12.5953 1.29538 12.6875 1.51794 12.6875 1.75V2.625ZM3.60325 3.5L3.5 3.55163V11.375C3.5 11.6071 3.59219 11.8296 3.75628 11.9937C3.92038 12.1578 4.14294 12.25 4.375 12.25H9.625C9.85706 12.25 10.0796 12.1578 10.2437 11.9937C10.4078 11.8296 10.5 11.6071 10.5 11.375V3.55163L10.3967 3.5H3.60325ZM2.1875 2.625H11.8125V1.75H2.1875V2.625Z" fill="#EC4427"/>
                                </svg>

                                Удалить
                            </Link>
                        </AppTooltipItem>
                    </AppTooltip>
                </div>
            </div>
            <div class="project-card__body">
                <ProjectStatistics :accounts-total="project.accountsTotal"></ProjectStatistics>
            </div>
            <div class="project-card__footer">
                <Link
                    :href="'/projects/' + project.id + '/accounts'"
                    :class="['btn', '--rounded', '--primary', '--xl', '--outline']">
                    Подробнее
                </Link>
            </div>
        </div>

        <UpdateProject
            ref="updateModal"
            @updateProjectsList="getProjects"
            :projectData="project"
        />
    </div>
</template>

<style scoped lang="scss">
@mixin default-border($border-color: rgba(21, 21, 21, 0.08), $border-radius: 12px){
    border: 1px solid $border-color;
    border-radius: $border-radius;
}

.project-card{
    border: 1px solid rgba(21,21,21, .08);
    padding: 19px 23px 28px 23px;
    border-radius: 10px;

    &__content{
        width: 100%;
        display: flex;
        flex-direction: column;
    }

    &__head{
        margin-bottom: 27px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    &__opts{
        position: relative;
    }

    &__body{
        margin-bottom: 27px;
    }

    &__title{
        font-size: 21px;
        font-weight: 550;
    }

    &__body{
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
}

:deep(.channels-info){
    display: flex;
    flex-direction: column;
    gap: 7px;
}

:deep(.channels-info__title){
    font-size: 12px;
    font-weight: 300;
    display: flex;
    gap: 5px;
    align-items: center;
    color:#545454;

    svg{
        width: 17px;
    }
}

:deep(.channels-info__mark){
    display: flex;
    align-items: center;
    gap: 4px;
}

:deep(.channels-info__mark-icon){
    display: flex;

    svg{
        width: 12px;
    }
}

:deep(.channels-info__mark-title){
    font-size: 10px;
    font-weight: 300;
    color:#545454;
}

:deep(.channels-info__stars){
    display: flex;
    gap: 1px;
    align-items: center;

    svg{
        width: 12px;
    }
}

:deep(.channels-stats){
    display: flex;
}

:deep(.channels-stats__item){
    width: 90px;
    display: flex;
    flex-direction: column;
    gap: 7px;
}

:deep(.channels-stats__title){
    font-size: 10px;
    font-weight: 300;
    color: rgba(21,21,21, .67)
}

:deep(.channels-stats__stats){
    display: flex;
    gap: 4px;
    align-items: center;
}

:deep(.channels-stats__stats-digit){
    font-size: 12px;
    font-weight: 500;
}

:deep(.channels-stats__stats-dynamic){
    font-weight: 500;
    font-size: 10px;
    display: flex;
    gap: 4px;
    align-items: end;

    &::before{
        display: block;
        content:"";
        width: 6px;
        height: 4px;
    }

    &.--positive{
        color: #36CE68;

        &::before{
            border: 4px solid transparent;
            border-bottom: 6px solid #36CE68;
        }
    }

    &.--negative{
        color: #EC4427;

        &::before{
            border: 4px solid transparent;
            border-top: 6px solid #EC4427;
        }
    }
}
</style>
