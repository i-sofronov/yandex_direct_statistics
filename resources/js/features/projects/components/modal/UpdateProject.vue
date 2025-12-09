<script setup lang="ts">
import { defineExpose, defineProps, inject, PropType, provide, reactive, watch } from 'vue';
import AppModal from "@/core/components/ui/modal/AppModal.vue"
import { useModal } from '@/core/composables/useModal'
import InputBlock from '@/core/components/ui/form/InputBlock.vue';
import ProjectService from "../../services/project.routing"
import { ValidationErrors } from '@/core/models';
import { Project } from '@/features/projects/models';
import AccountsTable from '@/features/projects/components/accounts/AccountsTable.vue';

defineEmits(['updateProjectsList'])

const { isOpen, open, close } = useModal(false);

let errors:ValidationErrors = reactive({} as ValidationErrors);

const props = defineProps({
    projectData: {
        type: Object as PropType<Project>,
        required: true
    }
})

const project = reactive({
    id: props.projectData.id,
    name: props.projectData.name,
    accounts: props.projectData.accounts,
})

async function saveProject(){
    const data = {
        name: project.name,
    }

    const response = await ProjectService.updateProject(project.id, data, false, true)

    if(response.error?.errors){
        errors = response.error.errors
    }
}

const onClose = () => {};

watch(() => props.projectData, (newData) => {
    project.accounts = newData.accounts
})

defineExpose({
    open: open,
    close: close
});
</script>

<template>
    <AppModal
        v-model="isOpen"
        title="Окно обновления проекта"
        size="md"
        @close="onClose"
    >

        <template #body>
            <div class="project-data">
                <InputBlock
                    v-model="project.name"
                    :inputID="'create-project-name'"
                    :inputPlaceholder="'Название проекта'"
                    :error="errors && errors.name"
                ></InputBlock>
            </div>

            <AccountsTable :accounts="project.accounts"></AccountsTable>

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
