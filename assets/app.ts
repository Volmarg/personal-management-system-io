import axios              from 'axios';
import VueAxios           from "vue-axios";
import ContainerComponent from "./vue/components/page/base/container.vue";
import Router             from './scripts/libs/vue/Router'
import AxiosCsrfPlugin    from "./vue/plugins/AxiosCsrfPlugin";
import TranslationPlugin  from "./vue/plugins/TranslationPlugin";

var Vue = require('vue');

/**
 * @description contains logic related to initializing Vue.js
 */
export default class App {

    /**
     * @description this one is used for all standard pages (consist of sidebar, footer etc)
     */
    static readonly MOUNTED_ELEMENT_ID_NAME_STANDARD_PAGE   = "vueApp";

    /**
     * @description this one is used for all special pages like authentication / 400 / 500 etc.
     */
    static readonly MOUNTED_ELEMENT_ID_NAME_BLANK_BASE_PAGE = "vueAppBlankBase";

    /**
     * @description Vue.js router
     */
    private router = new Router();

    /**
     * @description this id was used to mount given vue instance
     *              consist of "#" on front
     */
    private idUsedToMount ?:string = null;

    /**
     * @description initialize Vue logic
     */
    public init(): void
    {
        if( null !== document.getElementById(App.MOUNTED_ELEMENT_ID_NAME_STANDARD_PAGE) ){

            this.idUsedToMount = `#${App.MOUNTED_ELEMENT_ID_NAME_STANDARD_PAGE}`;
        }else if( null !== document.getElementById(App.MOUNTED_ELEMENT_ID_NAME_BLANK_BASE_PAGE )){
            //@ts-ignore
            ContainerComponent.props.includeBaseComponents = false;
            this.idUsedToMount = `#${App.MOUNTED_ELEMENT_ID_NAME_BLANK_BASE_PAGE}`;
        }else{
            throw {
                "info"           : "None of the supported id names has been found in dome",
                "additionalInfo" : "Maybe the selector is invalid or dom element does not exist",
            }
        }

        this.mountVueInstance();
    }

    /**
     * @description will mount vue instance
     */
    private mountVueInstance(): void
    {
        Vue.createApp(ContainerComponent)
            .use(this.router.getRouter())
            .use(VueAxios, axios)
            .use(this.getInstallablePlugins())
            .mount(this.idUsedToMount);
    }

    /**
     * @description will return plugins to be installed to Vue
     *              @see AxiosCsrfPlugin
     *              @see TranslationPlugin
     * @private
     */
    private getInstallablePlugins(): Object
    {
        return {
            install(app){
                app.config.globalProperties.postWithCsrf = AxiosCsrfPlugin.postWithCsrf;
                app.config.globalProperties.trans        = TranslationPlugin.trans;
            }
        }
    }
}