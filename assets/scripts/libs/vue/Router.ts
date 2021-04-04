import {createRouter, createWebHistory, RouterOptions}  from 'vue-router';

/**
 * @description Router used by vue
 */
export default class Router {

    /**
     * @description Definitions of vue routes
     */
    readonly routes : Array<Object> = [
        {
            // path      : "/modules/mailing/settings",
            // component : MailSettingsComponent,
            // name      : "modules_mailing_settings",
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