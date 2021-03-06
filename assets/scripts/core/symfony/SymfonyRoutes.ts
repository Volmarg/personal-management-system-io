import * as routes from '../../../../config/frontend/routes.json';
import StringUtils from "../utils/StringUtils";
import PmsError    from "../error/PmsError";

/**
 * @description This class contains definitions of INTERNAL api routes defined on backend side
 *              there is no way to pass this via templates etc so whenever a route is being changed in the symfony
 *              it also has to be updated here.
 *
 *              This solution was added to avoid for example calling routing api, or having string hardcoded in
 *              all the places.
 */
export default class SymfonyRoutes {

    /**
     * @description route used to fetch notes for given category id
     */
    static readonly ROUTE_NAME_GET_NOTES_FOR_CATEGORY_ID              = "module_notes_get_for_category";
    static readonly ROUTE_GET_NOTES_FOR_CATEGORY_ID_PARAM_CATEGORY_ID = "categoryId";

    /**
     * @description route used to fetch the notes categories hierarchy (parent - child (sub-categories))
     */
    static readonly ROUTE_NAME_GET_NOTES_CATEGORIES_HIERARCHY = 'module_notes_categories_get_parents_children_categories_hierarchy';

    /**
     * @description route used to fetch the notes (categories without hierarchy)
     */
    static readonly ROUTE_NAME_GET_NOTES_CATEGORIES = 'module_notes_categories_get_all';

    /**
     * @description displays note category page
     */
    static readonly ROUTE_NAME_MODULE_NOTES_CATEGORY          = "modules_notes_category";
    static readonly ROUTE_NAME_MODULE_NOTES_CATEGORY_ID_PARAM = "id";

    /**
     * @description displays dashboard page
     */
    static readonly ROUTE_NAME_MODULE_DASHBOARD_OVERVIEW = "modules_dashboard_overview";

    /**
     * @description displays passwords group
     */
    static readonly ROUTE_NAME_MODULE_PASSWORDS_GROUP          = "modules_passwords_group";
    static readonly ROUTE_NAME_MODULE_PASSWORDS_GROUP_ID_PARAM = "id";

    /**
     * @description gets all passwords groups
     */
    static readonly ROUTE_NAME_GET_ALL_PASSWORDS_GROUPS = "module_passwords_groups_get_all_groups";

    /**
     * @description will return passwords in given group id, alongside with some base information about password group
     */
    static readonly ROUTE_NAME_GET_PASSWORDS_FOR_GROUP_ID          = "module_passwords_get_for_group_id";
    static readonly ROUTE_NAME_GET_PASSWORDS_FOR_GROUP_ID_PARAM_ID = "id";

    /**
     * @description will decrypt the password of given id
     */
    static readonly ROUTE_NAME_DECRYPT_PASSWORD                   = "module_passwords_decrypt_password";
    static readonly ROUTE_NAME_DECRYPT_PASSWORD_PARAM_PASSWORD_ID = "passwordId";

    /**
     * @description login page + login authentication (form submission)
     */
    static readonly ROUTE_NAME_LOGIN = "login";

    /**
     * @description will return csrf token for form submission
     */
    static readonly ROUTE_NAME_GET_CSRF_TOKEN                = "system_get_csrf_token";
    static readonly ROUTE_NAME_GET_CSRF_TOKEN_PARAM_TOKEN_ID = "tokenId";

    /**
     * @description will return logged in user data
     */
    static readonly ROUTE_NAME_GET_LOGGED_IN_USER_DATA = "user_get_logged_in_user_data";

    /**
     * @description will invalidate the user
     *              Keep in mind that it's required to redirect user after invalidation
     */
    static readonly ROUTE_NAME_INVALIDATE_USER = "user_invalidate_user";

    /**
     * @description will display the search page
     */
    static readonly ROUTE_NAME_MODULE_SEARCH_SEARCH_OVERVIEW = "module_search_overview";

    /**
     * @description will fetch search results for provided parameters (POST)
     */
    static readonly ROUTE_NAME_MODULE_SEARCH_GET_RESULTS = "module_search_get_results";

    /**
     * @description will fetch all modules supported in the search logic (names with their translations)
     */
    static readonly ROUTE_NAME_MODULE_GET_ALL_TRANSLATED_MODULES_NAMES_FOR_SEARCH = "module_search_get_all_translated_modules_names";

    /**
     * @description return information if there is any data to work with
     */
    static readonly ROUTE_NAME_MODULE_IS_DATA_AVAILABLE                     = "module_is_module_data_available";
    static readonly ROUTE_NAME_MODULE_IS_DATA_AVAILABLE_PARAM_IS_FIRST_CALL = "isFirstCall";

    /**
     * Will get url path for route name
     * Exception is thrown is none match is found
     *
     * @param routeName       - name of the searched route
     * @param routeParameters - array of parameters that need to be replaced in the route
     *                          if not matching parameter is found then warning log is thrown and next
     *                          parameter will be processed
     */
    public static getPathForName(routeName: string, routeParameters: Object = {}): string
    {
        // get route
        let matchingRoutePath = routes[routeName];
        if( StringUtils.isEmptyString(matchingRoutePath) ){
            throw new PmsError("No matching route was route was found for given name: " + routeName);
        }

        // replace params
        let keys = Object.keys(routeParameters);
        keys.forEach( (parameter) => {

            if( !matchingRoutePath.includes(":" + parameter) ){
                console.warn({
                    "info"      : "Provided path does not contain given parameter",
                    "parameter" : parameter,
                })
            }else{
                let value         = routeParameters[parameter];
                matchingRoutePath = matchingRoutePath.replace(":" + parameter, value);
            }

        })

        return matchingRoutePath;
    }

}