import Account, {
    AccountRawDataInterface,
    YandexDirectInterface,
} from '@/features/projects/models/Account.model';

export interface ProjectRawDataInterface{
    id: number,
    name: string,
    accounts?: AccountRawDataInterface[],
    yandex_direct?: ProjectDirectTotalInterface,
    yandex_metrika?: ProjectMetrikaTotalInterface,
    yandex_business?: ProjectBusinessTotalInterface,
    socials?: ProjectSocialsTotalInterface,
}

export interface ProjectUpdateDataInterface{
    name: string
}

export interface ProjectCreateDataInterface{
    name: string
    user_id: number
}

export interface ProjectDirectTotalInterface {
    is_empty: boolean,
    conversions: {
        current: number,
        dynamic: number
    },
    cpa: {
        current: number,
        dynamic: number
    },
}

export interface ProjectMetrikaTotalInterface {
    is_empty: boolean,
    pageviews: {
        current: number,
        dynamic: number
    },
    visits: {
        current: number,
        dynamic: number
    },
}

export interface ProjectBusinessTotalInterface {
    is_empty: boolean,
    feedbacks: {
        current: number,
        dynamic: number
    },
    rating: {
        current: number,
        dynamic: number
    },
    routes_clicks: {
        current: number,
        dynamic: number
    },
}

export interface ProjectSocialsTotalInterface {
    is_empty: boolean,
    followers: {
        current: number,
        dynamic: number
    },
    views: {
        current: number,
        dynamic: number
    },
}

class Project {
    public id: number
    public name: string
    public accounts: Account[]
    public accountsTotal?: {
        yandexDirect?: ProjectDirectTotalInterface
        yandexMetrika?: ProjectMetrikaTotalInterface
        yandexBusiness?: ProjectBusinessTotalInterface
        socials?: ProjectSocialsTotalInterface
    }

    constructor(project: ProjectRawDataInterface) {
        this.id = project.id
        this.name = project.name
        this.accounts = (project.accounts || []).map(account => new Account(account))

        this.accountsTotal = {}

        if(project.yandex_direct){
            this.accountsTotal.yandexDirect = project.yandex_direct
        }

        if(project.yandex_metrika){
            this.accountsTotal.yandexMetrika = project.yandex_metrika
        }

        if(project.yandex_business){
            this.accountsTotal.yandexBusiness = project.yandex_business
        }
        console.log(project);
        if(project.socials){
            this.accountsTotal.socials = project.socials
        }

        return this
    }
}

export default Project

