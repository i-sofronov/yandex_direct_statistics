<script setup lang="ts">
import { usePage } from '@inertiajs/vue3'
import { watch } from 'vue'
import { useNotification } from '@/core/composables/useNotification'

const { success, error } = useNotification()
const page = usePage()


watch(
    () => page.props.flash,
    (flash: any) => {
        if (flash?.success) {
            success('Успех!', flash.success)
        }

        if (flash?.error) {
            error('Ошибка!', flash.error)
        }
    },
    { immediate: true, deep: true }
)
</script>

<template></template>
