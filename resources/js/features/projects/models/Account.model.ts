import { ProjectRawDataInterface } from '@/features/projects/models/Project.model';
import { Project } from '@/features/projects/models/index';

export interface YandexDirectTotalInterface{
    clicks: number,
    impressions: number,
    ctr: number,
    conversions: number,
    cpc: number,
    cpa: number,
    cost: number,
    dynamic: {
        clicks: number
        impressions: number
        ctr: number
        conversions: number
        cost: number
        cpc: number
        cpa: number
    },
}

export interface YandexDirectInterface{
    id: number,
    account_id: number,
    date: string,
    impressions: number,
    clicks: number,
    ctr: number,
    avg_cpc: number,
    cost: number,
    conversions: number,
    cost_per_conversion: number,
    conversion_rate: number,
    created_at: string,
    updated_at: string,
    total?: YandexDirectTotalInterface
}

export interface AccountRawDataInterface{
    id: number,
    project_id: number,
    name: string,
    created_at: string,
    updated_at: string,
    metadata: string,
    project?: ProjectRawDataInterface,
    yandex_direct_statistics?: YandexDirectInterface[],
    account_type: AccountTypes,
    total?: YandexDirectTotalInterface,
}

export type AccountTypes = 'yandex_direct'

class Account {
    public id: number
    public name: string
    public projectId: number
    public metadata: object
    public project?: Project
    public yandexDirect?: YandexDirectInterface[]
    public accountType: AccountTypes
    public total?: YandexDirectTotalInterface
    public createdAt: string
    public updatedAt: string

    constructor(account: AccountRawDataInterface) {

        this.id = account.id
        this.name = account.name
        this.projectId = account.project_id
        this.accountType = account.account_type
        this.metadata = account.metadata ? JSON.parse(account.metadata) : '';

        this.createdAt = account.created_at
        this.updatedAt = account.updated_at

        if(account.project){
            this.project = new Project(account.project)
        }

        if(account.yandex_direct_statistics){
            this.yandexDirect = account.yandex_direct_statistics
        }

        if(account.total){
            switch(this.accountType){
                case 'yandex_direct':
                    this.total = account.total as YandexDirectTotalInterface
                    break;
            }
        }

        return this
    }

    get directTotal(): YandexDirectTotalInterface | undefined {
        return this.isDirect() ? this.total as YandexDirectTotalInterface : undefined;
    }

    isDirect(){
        return this.accountType === 'yandex_direct'
    }
}

export default Account
