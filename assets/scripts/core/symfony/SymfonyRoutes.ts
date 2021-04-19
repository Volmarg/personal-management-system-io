import * as routes from '../../../../config/frontend/routes.json';
import StringUtils from "../utils/StringUtils";

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
            throw {
                "info"         : "No matching route was route was found for given name",
                "searchedName" : routeName,
            }
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