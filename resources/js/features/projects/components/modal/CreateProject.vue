<script setup lang="ts">
import { defineExpose, reactive, Ref, ref } from 'vue';
import AppModal from "@/core/components/ui/modal/AppModal.vue"
import { useModal } from '@/core/composables/useModal'
import InputBlock from '@/core/components/ui/form/InputBlock.vue';
import ProjectService from "../../services/project.routing"
import { Project } from '@/features/projects/models';
import { ValidationErrors } from '@/core/models';
import { useNotification } from '@/core/composables/useNotification';

const emit = defineEmits(['updateProjectsList'])

const { isOpen, open, close } = useModal(false);

const project:Ref<Project> = ref({} as Project);
let errors:ValidationErrors = reactive({} as ValidationErrors);

const projectData = reactive({
    name: ''
})

async function saveProject(){
    const data = {
        ...projectData,
        user_id: 1
    }

    const response = await ProjectService.createProject(data, false, true, ['initialProjects'])

    if(response.error?.errors){
        errors = response.error.errors
    }

    close()
}

const onClose = () => {
    projectData.name = '';

    if(project.value.id){
        emit('updateProjectsList');
    }
};

defineExpose({
    open: open,
    close: close
});
</script>

<template>
    <AppModal
        v-model="isOpen"
        title="Создать проект"
        size="md"
        @close="onClose"
    >

        <template #body>
            <div class="project-data">
                <InputBlock
                    v-model="projectData.name"
                    :inputID="'create-project-name'"
                    :inputPlaceholder="'Название проекта'"
                    :error="errors && errors.name"
                ></InputBlock>
            </div>
        </template>

        <template #footer>
            <div class="modal__btns">
                <button @click="saveProject" class="btn --primary --filled --xxl">Сохранить данные</button>
            </div>
        </template>
    </AppModal>
</template>

<style scoped lang="scss">

</style>
