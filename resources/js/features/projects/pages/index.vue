<script setup lang="ts">
import AppLayout from '@/core/components/layouts/AppLayout.vue';
import PlusIcon from '@/core/components/ui/icons/PlusIcon.vue';
import ProjectCard from '@/features/projects/components/project-card/index.vue';
import CreateProjectModal from "@/features/projects/components/modal/CreateProject.vue"
import { ref, defineProps, PropType, onMounted, onUpdated, Ref, provide, watch, onBeforeMount } from 'vue';
import type { ProjectRawDataInterface } from '@/features/projects/models';
import { Project } from '@/features/projects/models';
import ProjectApi from '@/features/projects/services/project.routing';
import ModuleLayout from '@/features/projects/components/layouts/module/ModuleLayout.vue';
import ModuleTitle from '@/features/projects/components/layouts/module/ModuleTitle.vue';
import ModuleButtons from '@/features/projects/components/layouts/module/ModuleButtons.vue';
import ModuleCalendar from '@/features/projects/components/layouts/module/ModuleCalendar.vue';
import ModuleSearch from '@/features/projects/components/layouts/module/ModuleSearch.vue';
import ModuleFilter from '@/features/projects/components/layouts/module/ModuleFilter.vue';
import ModuleListSettings from '@/features/projects/components/layouts/module/ModuleListSettings.vue';
import ModuleCards from '@/features/projects/components/layouts/module/ModuleCards.vue';
import ModuleCardsEmpty from '@/features/projects/components/layouts/module/ModuleCardsEmpty.vue';

const props = defineProps({
    'initialProjects': {
        type: Array as PropType<ProjectRawDataInterface[]>,
        required: true
    },
    'filters': {
        type: Object as PropType<{
            start_date: string,
            end_date: string
        }>,
        required: true
    },
})

const createModal = ref<{ open: () => void; close: () => void }>();

const projects: Ref<Project[]> = ref([])

watch(() => props.initialProjects, () => {
    projects.value = (props.initialProjects || []).map((project: ProjectRawDataInterface) => { return new Project(project) } )
})

const updatedProjectId: Ref<null|string> = ref(null)

onMounted( () => {
    projects.value = (props.initialProjects || []).map((project: ProjectRawDataInterface) => { return new Project(project) } )

    const modalName = findGetParameter('modalName')

    if(modalName === 'editProject'){
        updatedProjectId.value = findGetParameter('updatedProjectId')
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

    await ProjectApi.getProjects(filterData, false, true);
}

function filterProjects(event: string){
    if(event.length > 0){
        const initialProjects = props.initialProjects;
        const filtredProjects = initialProjects.filter(project => project.name.includes(event))

        projects.value = (filtredProjects || []).map((project: ProjectRawDataInterface) => { return new Project(project) } )
    }
    else{
        projects.value = (props.initialProjects || []).map((project: ProjectRawDataInterface) => { return new Project(project) } )
    }
}

function findGetParameter(parameterName: string) {
    let result = null,
        tmp = [];
    location.search
        .substr(1)
        .split("&")
        .forEach(function (item) {
            tmp = item.split("=");
            if (tmp[0] === parameterName) result = decodeURIComponent(tmp[1]);
        });
    return result;
}
</script>

<template>
<AppLayout>
    <ModuleLayout>
        <template #module-head-middle>
            <ModuleTitle title="Проекты"></ModuleTitle>

            <ModuleButtons>
                <button @click="createModal && createModal.open()" class="btn --rounded --primary --md --outline">
                    <PlusIcon></PlusIcon>
                    Добавить проект
                </button>
            </ModuleButtons>

            <ModuleCalendar :start-date="filters.start_date" :end-date="filters.end_date" @calendarRangeUpdated="getProjects"></ModuleCalendar>
        </template>

        <template #module-head-bottom>
            <ModuleSearch @input="filterProjects($event)"></ModuleSearch>

            <ModuleFilter @click="console.log('Module Filter Clicked')"></ModuleFilter>

            <ModuleListSettings></ModuleListSettings>
        </template>

        <template #module-body>
            <ModuleCards v-if="projects && projects.length > 0">
                <ProjectCard
                    v-for="project in projects"
                    v-bind:key="project.id"
                    :project="project"
                    :updated-project-id="Number(updatedProjectId)"
                ></ProjectCard>
            </ModuleCards>
            <ModuleCardsEmpty v-else>
                Проектов нет
            </ModuleCardsEmpty>
        </template>
    </ModuleLayout>

    <CreateProjectModal
        ref="createModal"
        @updateProjectsList="getProjects"
    />
</AppLayout>
</template>

<style scoped lang="scss">
</style>
