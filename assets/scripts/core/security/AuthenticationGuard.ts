/**
 * @description this class handles checking user authentication for given pages, for example
 *              controls the flow of requests from Axios and redirect the user in case of missing authentication
 */
import BaseApiDto   from "../dto/BaseApiDto";
import StringUtils                  from "../utils/StringUtils";
import SymfonyRoutes                from "../symfony/SymfonyRoutes";
import TranslationsService from "../service/TranslationsService";
import ToastifyService from "../../libs/toastify/ToastifyService";

export default class AuthenticationGuard
{

    private translationService = new TranslationsService();

    /**
     * @description will check if user has access to given route and if response contains redirection route
     *              then user is redirected (pushed in history) to other page
     *
     *              This is possible thx to all request sharing common base
     *              @see BaseApiDto
     *
     * @return boolean - true if everything is ok, false upon authentication issues
     */
    public checkRouteCallAuthentication(axiosResponse: object): boolean
    {
        let baseApiResponse = BaseApiDto.fromAxiosResponse(axiosResponse);
        if(
                !baseApiResponse.success
            &&  !StringUtils.isEmptyString(baseApiResponse.redirectRouteName)
            &&  location.pathname !== SymfonyRoutes.getPathForName(baseApiResponse.redirectRouteName)
        )
        {
            let message   = this.translationService.getTranslationForString("security.login.messages.UNAUTHORIZED");
            location.href = SymfonyRoutes.getPathForName((baseApiResponse.redirectRouteName));
            ToastifyService.showOrangeNotification(message)

            return false;
        }
        return true;
    }

}