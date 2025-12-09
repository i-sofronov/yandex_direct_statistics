import { ref, provide, inject, type Ref } from 'vue'

interface NotificationState {
    show: boolean
    title: string
    text: string
    type: 'success' | 'error'
    duration: number
}

// Глобальное реактивное состояние
const globalNotification = ref<NotificationState>({
    show: false,
    title: '',
    text: '',
    type: 'success',
    duration: 5000
})

// Флаг чтобы избежать дублирования ворнингов
let warningShown = false

const globalApi = {
    notification: globalNotification,
    show: (options: Partial<NotificationState>) => {
        const newState = {
            ...globalNotification.value,
            ...options,
            show: true
        }

        globalNotification.value = newState

        // Автоскрытие
        const duration = options.duration || 5000
        if (duration > 0) {
            setTimeout(() => {
                globalNotification.value.show = false
            }, duration)
        }
    },
    hide: () => {
        globalNotification.value.show = false
    },
    success: (title: string, text: string, duration = 5000) => {
        globalApi.show({ title, text, type: 'success', duration })
    },
    error: (title: string, text: string, duration = 5000) => {
        globalApi.show({ title, text, type: 'error', duration })
    }
}

export function useNotificationProvider() {
    provide('notification', globalApi)
    return globalApi
}

export function useNotification() {
    const notificationApi = inject<ReturnType<typeof useNotificationProvider>>('notification')

    if (!notificationApi) {
        if (!warningShown) {
            console.warn('useNotification: Provider not found. Make sure to call useNotificationProvider() in a parent component like AppLayout.')
            warningShown = true
        }
        return globalApi
    }

    return notificationApi
}
