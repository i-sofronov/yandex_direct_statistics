import { InertiaRoutingService, requestOptionsInterface } from '@/core/services/base.routing';
import type { ProjectCreateDataInterface, ProjectRawDataInterface, ProjectUpdateDataInterface } from '../models';
import { Project } from '../models'
import { ApiError } from '@/core/models';
import { router } from '@inertiajs/vue3';
import Account, { AccountRawDataInterface } from '@/features/projects/models/Account.model';

class ProjectService extends InertiaRoutingService {
    private static instance: ProjectService

    public static getInstance(): ProjectService {
        if (!ProjectService.instance) {
            ProjectService.instance = new ProjectService()
        }
        return ProjectService.instance
    }

    private constructor() {
        super()
    }

    async getProjects(
        filters: any = {},
        preserveScroll: boolean = false,
        preserveState: boolean = false,
        only: string[] = []
    ): Promise<{ data?: ProjectRawDataInterface[]; error?: ApiError }> {
        return this.handleInertiaRequest(
            () => {
                return new Promise((resolve, reject) => {
                    const options = {} as requestOptionsInterface;

                    if(preserveScroll){
                        options.preserveScroll = preserveScroll
                    }
                    if(preserveState){
                        options.preserveState = preserveState
                    }
                    if(only){
                        options.only = only
                    }

                    router.get('/projects', filters, {
                        ...options,
                        onSuccess: (page) => {
                            const projects = (page.props.initialProjects || []) as ProjectRawDataInterface[]

                            resolve(projects)
                        },
                        onError: (errors) => {
                            reject(errors)
                        }
                    })
                })
            },
            'загрузке проектов'
        )
    }

    async getProject(
        id: number,
        preserveScroll: boolean = false,
        preserveState: boolean = false,
        only: string[] = []
    ): Promise<{ data?: Project; error?: ApiError }> {
        return this.handleInertiaRequest(
            () => {
                return new Promise((resolve, reject) => {
                    const options = {} as requestOptionsInterface;

                    if(preserveScroll){
                        options.preserveScroll = preserveScroll
                    }
                    if(preserveState){
                        options.preserveScroll = preserveState
                    }
                    if(only){
                        options.only = only
                    }

                    router.get(`/projects/${id}`, {}, {
                        ...options,
                        onSuccess: (page: any) => {
                            const project = new Project(page.props.project as ProjectRawDataInterface)

                            resolve(project)
                        },
                        onError: (errors) => {
                            reject(errors)
                        }
                    })
                })
            },
            'загрузке проекта'
        )
    }

    async createProject(
        data: ProjectCreateDataInterface,
        preserveScroll: boolean = false,
        preserveState: boolean = false,
        only: string[] = []
    ): Promise<{ data?: ProjectRawDataInterface; error?: ApiError }> {
        return this.handleInertiaRequest(
            () => {
                return new Promise((resolve, reject) => {
                    const payload = { ...data }
                    const options = {} as requestOptionsInterface;

                    if(preserveScroll){
                        options.preserveScroll = preserveScroll
                    }
                    if(preserveState){
                        options.preserveScroll = preserveState
                    }
                    if(only){
                        options.only = only
                    }

                    router.post('/projects', payload, {
                        ...options,
                        onSuccess: (page: any) => {
                            const project = page.props.project as ProjectRawDataInterface

                            resolve(project)
                        },
                        onError: (errors) => {
                            reject(errors)
                        }
                    })
                })
            },
            'создании проекта',
            'Список проектов обновлен'
        )
    }

    async updateProject(
        id: number,
        data: ProjectUpdateDataInterface,
        preserveScroll: boolean = false,
        preserveState: boolean = false,
        only: string[] = []
    ): Promise<{ data?: ProjectRawDataInterface; error?: ApiError }> {
        return this.handleInertiaRequest(
            () => {
                return new Promise((resolve, reject) => {
                    const payload = { ...data }
                    const options = {} as requestOptionsInterface;

                    if(preserveScroll){
                        options.preserveScroll = preserveScroll
                    }
                    if(preserveState){
                        options.preserveScroll = preserveState
                    }
                    if(only){
                        options.only = only
                    }

                    router.put(`/projects/${id}`, payload, {
                        ...options,
                        onSuccess: (page) => {
                            const project = page.props.project as ProjectRawDataInterface
                            resolve(project)
                        },
                        onError: (errors) => {
                            console.log(errors);
                            reject(errors)
                        }
                    })
                })
            },
            'обновлении проекта',
            'Проект успешно обновлен'
        )
    }

    async deleteProject(
        id: number,
        preserveScroll: boolean = false,
        preserveState: boolean = false,
        only: string[] = []
    ): Promise<{ error?: ApiError }> {
        return this.handleInertiaRequest(
            () => {
                return new Promise((resolve, reject) => {
                    const options = {} as requestOptionsInterface;

                    if(preserveScroll){
                        options.preserveScroll = preserveScroll
                    }
                    if(preserveState){
                        options.preserveScroll = preserveState
                    }
                    if(only){
                        options.only = only
                    }

                    router.delete(`/projects/${id}`, {
                        ...options,
                        onSuccess: () => {
                            resolve(undefined)
                        },
                        onError: (errors) => {
                            reject(errors)
                        }
                    })
                })
            },
            'удалении проекта',
            'Проект успешно удален'
        )
    }

    async getAccounts(
        id: number,
        filters: any = {},
        preserveScroll: boolean = false,
        preserveState: boolean = false,
        only: string[] = []
    ): Promise<{ data?: { accounts: Account[], initialProject: Project | null }, error?: ApiError }> {
        return this.handleInertiaRequest(
            () => {
                return new Promise((resolve, reject) => {
                    const options = {} as requestOptionsInterface;

                    if(preserveScroll){
                        options.preserveScroll = preserveScroll
                    }
                    if(preserveState){
                        options.preserveState = preserveState
                    }
                    if(only){
                        options.only = only
                    }

                    router.get(`/projects/${id}/accounts`,
                        filters,
                        {
                            ...options,
                            onSuccess: (page: any) => {
                                resolve({
                                    accounts: page.props.accounts ? (page.props.accounts as AccountRawDataInterface[]).map(account => new Account(account)) : [],
                                    initialProject: page.props.initialProject ? new Project(page.props.initialProject as ProjectRawDataInterface) : null
                                })
                            },
                            onError: (errors: ApiError) => {
                                reject(errors)
                            }
                        }
                    )
                })
            },
            'получении списка аккаунтов',
            'Аккаунты успешно получены'
        )
    }

    async getAccount(
        projectId: number,
        accountId: number,
        filters: any = {},
        preserveScroll: boolean = false,
        preserveState: boolean = false,
        only: string[] = []
    ): Promise<{ data?: { initialAccount: Account | null, initialProject: Project | null }, error?: ApiError }> {
        return this.handleInertiaRequest(
            () => {
                return new Promise((resolve, reject) => {
                    const options = {} as requestOptionsInterface;

                    if(preserveScroll){
                        options.preserveScroll = preserveScroll
                    }
                    if(preserveState){
                        options.preserveState = preserveState
                    }
                    if(only){
                        options.only = only
                    }

                    router.get(`/projects/${projectId}/accounts/${accountId}`,
                        filters,
                        {
                            ...options,
                            onSuccess: (page: any) => {
                                resolve({
                                    initialAccount: page.props.initialAccount ? new Account(page.props.initialAccount as AccountRawDataInterface) : null,
                                    initialProject: page.props.initialProject ? new Project(page.props.initialProject as ProjectRawDataInterface) : null
                                })
                            },
                            onError: (errors: ApiError) => {
                                reject(errors)
                            }
                        }
                    )
                })
            },
            'получении данных аккаунта',
            'Данные аккаунта успешно получены'
        )
    }
}

export default ProjectService.getInstance() as ProjectService
