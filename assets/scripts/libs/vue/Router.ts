import {createRouter, createWebHistory, RouteRecordRaw, RouterOptions} from 'vue-router';
import NoteCategoryComponent from '../../../vue/pages/module/notes/category.vue';

/**
 * @description Router used by vue
 */
export default class Router {

    /**
     * @description Definitions of vue routes
     */
    readonly routes : Array<RouteRecordRaw> = [
        {
            path      : "/module/notes/category/:id",
            component : NoteCategoryComponent,
            name      : "module_notes_category",
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