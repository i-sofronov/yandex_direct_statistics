import type { ApiError } from '@/core/models'
import { useNotification } from '@/core/composables/useNotification'

const { success, error } = useNotification()

export interface requestOptionsInterface{
    preserveScroll: boolean,
    preserveState: boolean,
    only: string[]
}

export abstract class InertiaRoutingService {
    protected constructor() {}

    protected async handleInertiaRequest<T>(
        request: () => Promise<T>,
        context: string = 'операции',
        successMessage?: string
    ): Promise<{ data?: T; error?: ApiError }> {
        try {
            const result = await request()

            if (successMessage) {
                this.showSuccess(successMessage)
            }

            return { data: result }
        } catch (inertiaError: any) {
            const apiError = this.handleInertiaError(inertiaError, context)
            return { error: apiError }
        }
    }

    protected handleInertiaError(inertiaError: any, context: string = 'операции'): ApiError {
        const error = {} as ApiError;

        if (inertiaError?.message) {
            error.message = inertiaError.message
        }
        else{
            error.message = `Ошибка при ${context}`
        }

        if (inertiaError?.errors) {
            error.errors = inertiaError.errors;
        }

        this.showError(error.message)

        return error
    }

    protected showError(message: string): void {
        error('Внимание', message || 'Произошла ошибка')
    }

    protected showSuccess(message: string): void {
        success('Успешно', message)
    }
}
