import {createRouter, createWebHistory, RouteRecordRaw, RouterOptions} from 'vue-router';
import NoteCategoryComponent      from '../../../vue/pages/module/notes/category.vue';
import DashboardOverviewComponent from '../../../vue/pages/module/dashboard/overview.vue';

/**
 * @description Router used by vue
 */
export default class Router {

    static readonly ROUTE_NAME_MODULE_NOTES_CATEGORY          = "module_notes_category";
    static readonly ROUTE_NAME_MODULE_NOTES_CATEGORY_ID_PARAM = "id";

    static readonly ROUTE_NAME_MODULE_DASHBOARD_OVERVIEW = "module_dashboard_overview";
    static readonly ROUTE_NAME_HASH                      = "hash";

    /**
     * @description Definitions of vue routes
     */
    readonly routes : Array<RouteRecordRaw> = [
        {
            path      : "/module/notes/category/:id",
            component : NoteCategoryComponent,
            name      : Router.ROUTE_NAME_MODULE_NOTES_CATEGORY,
        },
        {
            path      : "/module/dashboard/overview",
            component : DashboardOverviewComponent,
            name      : Router.ROUTE_NAME_MODULE_DASHBOARD_OVERVIEW,
        },
        {
            path: "/#",
            redirect: "#",
            name: Router.ROUTE_NAME_HASH,
        }
    ];

    /**
     * @description returns the vue router
     */
    public getRouter(): Router {
        let vueRouterOptions = {
            routes: this.routes,
            history : createWebHistory(),
        } as RouterOptions;

        let router = createRouter(vueRouterOptions)

        //@ts-ignore
        return router;
    }

}