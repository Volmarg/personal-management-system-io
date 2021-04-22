import {createRouter, createWebHistory, RouteRecordRaw, RouterOptions} from 'vue-router';
import NoteCategoryComponent      from '../../../vue/pages/module/notes/category.vue';
import DashboardOverviewComponent from '../../../vue/pages/module/dashboard/overview.vue';
import PasswordsOverviewComponent from '../../../vue/pages/module/passwords/passwords.vue';
import LoginPageComponent         from "../../../vue/pages/module/secuirty/login.vue";
import SymfonyRoutes              from "../../core/symfony/SymfonyRoutes";

/**
 * @description Router used by vue
 */
export default class Router {

    /**
     * @description not clickable link ending with hash
     */
    static readonly ROUTE_NAME_HASH = "hash";

    /**
     * @description Definitions of vue routes
     */
    readonly routes : Array<RouteRecordRaw> = [
        {
            component : NoteCategoryComponent,
            path      : SymfonyRoutes.getPathForName(SymfonyRoutes.ROUTE_NAME_MODULE_NOTES_CATEGORY),
            name      : SymfonyRoutes.ROUTE_NAME_MODULE_NOTES_CATEGORY,
        },
        {
            component : DashboardOverviewComponent,
            path      : SymfonyRoutes.getPathForName(SymfonyRoutes.ROUTE_NAME_MODULE_DASHBOARD_OVERVIEW),
            name      : SymfonyRoutes.ROUTE_NAME_MODULE_DASHBOARD_OVERVIEW,
        },
        {
            redirect : "#",
            path     : "/#",
            name     : Router.ROUTE_NAME_HASH,
        },
        {
            component : PasswordsOverviewComponent,
            path      : SymfonyRoutes.getPathForName(SymfonyRoutes.ROUTE_NAME_MODULE_PASSWORDS_GROUP),
            name      : SymfonyRoutes.ROUTE_NAME_MODULE_PASSWORDS_GROUP,
        },
        {
            component : LoginPageComponent,
            path      : SymfonyRoutes.getPathForName(SymfonyRoutes.ROUTE_NAME_LOGIN),
            name      : SymfonyRoutes.ROUTE_NAME_LOGIN,
        }
    ];

    /**
     * @description returns the vue router
     */
    public getRouter(): Router
    {
        let vueRouterOptions = {
            routes: this.routes,
            history : createWebHistory(),
        } as RouterOptions;

        let router = createRouter(vueRouterOptions)

        //@ts-ignore
        return router;
    }

}