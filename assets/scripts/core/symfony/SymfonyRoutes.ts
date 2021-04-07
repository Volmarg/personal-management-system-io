/**
 * @description This class contains definitions of INTERNAL api routes defined on backend side
 *              there is no way to pass this via templates etc so whenever a route is being changed in the symfony
 *              it also has to be updated here.
 *
 *              This solution was added to avoid for example calling translation api and having string hardcoded in
 *              all the places.
 */
export default class SymfonyRoutes {

    /**
     * @description route used to fetch notes for given category id
     */
    static readonly GET_NOTES_FOR_CATEGORY_ID                   = "/module/notes/get-for-category/{categoryId}";
    static readonly GET_NOTES_FOR_CATEGORY_ID_PARAM_CATEGORY_ID = "{categoryId}";

    /**
     * @description returns the information if system demo mode is on or not
     */
    static readonly ENV_IS_DEMO = "/api-internal/env/is-demo";

    /**
     * @description will use the url with params and replace the params with values
     */
    public static buildUrlWithReplacedParams(processedUrl: string, replacedParamsWithValues: object): string
    {
        let keys = Object.keys(replacedParamsWithValues);
        keys.forEach( (key, index) => {
            let value = replacedParamsWithValues[key];
            processedUrl = processedUrl.replace(key, value);
        })

        return processedUrl;
    }

}