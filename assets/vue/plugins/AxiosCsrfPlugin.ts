import SymfonyRoutes    from "../../scripts/core/symfony/SymfonyRoutes";
import CsrfTokenDto     from "../../scripts/core/dto/CsrfTokenDto";
import BaseApiDto       from "../../scripts/core/dto/BaseApiDto";
import Axios            from "../../scripts/libs/axios/Axios";
import PmsError         from "../../scripts/core/error/PmsError";

import { v4 as uuidv4 } from 'uuid';

/**
 * @description this plugin handles axios call (POST), but before doing the main call it will first call Symfony backend
 *              to obtain the csrf token. Obtained token is then added to the request bag of main request.
 *
 *              The goal of such solution is to have one, common, reusable and separated logic to submit forms
 *
 *              It's required that each Plugin uses only static methods as context of `this` is lost once the
 *              plugin is injected into the VueApp.
 *
 *              Install method is not implemented here as otherwise the class structure would not be allowed.
 */
export default class AxiosCsrfPlugin
{
    /**
     * @description key that contains csrf token in normal form submission in symfony
     */
    static readonly SYMFONY_KEY_CSRF_TOKEN = "_token";

    /**
     * @description makes the post call but first fetches the csrf for further submission
     */
    public static async postWithCsrf(calledUrl: string, dataBag: Object): Promise<any>
    {
        try{
            let csrfTokenId = uuidv4();
            Axios.setHeader("csrfTokenId", csrfTokenId.toString());

            let csrfToken             = await AxiosCsrfPlugin.callForCsrf(csrfTokenId);
            let handlePostCallPromise = new Promise( (resolve, reject) => {

                let extendedDataBag = {...dataBag,
                    [AxiosCsrfPlugin.SYMFONY_KEY_CSRF_TOKEN] : csrfToken,
                };

                return Axios.post(calledUrl, extendedDataBag).then( result => {
                    let baseResponse = BaseApiDto.fromAxiosResponse(result);
                    resolve(baseResponse);
                })
            });

            return handlePostCallPromise;
        }catch(Exception){
            throw new PmsError("There were issues with POST request with CSRF token fetch", Exception);
        }
    }

    /**
     * @description makes axios call for CSRF token
     */
    public static callForCsrf(csrfTokenId): Promise<any>
    {
        let calledUrl = SymfonyRoutes.getPathForName(SymfonyRoutes.ROUTE_NAME_GET_CSRF_TOKEN, {
            [SymfonyRoutes.ROUTE_NAME_GET_CSRF_TOKEN_PARAM_TOKEN_ID] : csrfTokenId,
        });
        let promise = Axios.get(calledUrl).then( result => {

            let csrfTokenResponse = CsrfTokenDto.fromAxiosResponse(result);
            if( !csrfTokenResponse.success ){
                AxiosCsrfPlugin.throwIssueObtainingCsrfTokenMessage(csrfTokenResponse);
                return;
            }

            return csrfTokenResponse.csrToken;
        }).catch(  result => {
            AxiosCsrfPlugin.throwIssueObtainingCsrfTokenMessage(result);
        });

        return promise;
    }

    /**
     * @description will throw information about regarding issues in fetching the CSRF token
     */
    private static throwIssueObtainingCsrfTokenMessage(data: any): void
    {
        throw new PmsError("There were some issues with obtaining the csrf token", data);
    }

}