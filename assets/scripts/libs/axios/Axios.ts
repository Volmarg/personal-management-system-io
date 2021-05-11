import axios               from 'axios';
import AuthenticationGuard from "../../core/security/AuthenticationGuard";

/**
 * @description axios with adjustments toward Symfony
 */
export default class Axios
{
    static readonly METHOD_POST = "post";
    static readonly METHOD_GET  = "get";

    static readonly ALL_SUPPORTED_METHODS = [
      Axios.METHOD_POST,
      Axios.METHOD_GET,
    ];

    private static authenticationGuard = new AuthenticationGuard();

    /**
     * @description will make get call
     */
    public static get(url: string, data:object = {}): Promise<any>
    {
        return Axios.makeCall(Axios.METHOD_GET, url, data);
    }

    /**
     * @description will make post call
     */
    public static post(url: string, data:object): Promise<any>
    {
        return Axios.makeCall(Axios.METHOD_POST, url, data);
    }

    /**
     * @description will add headers to given axios instance
     */
    public static setHeader(key: string, value: string): void
    {
        axios.defaults.headers.common[key] = value;
    }

    /**
     * @description makes axios call
     */
    private static async makeCall(method: string, url: string, data: object): Promise<any>
    {
        if( !Axios.ALL_SUPPORTED_METHODS.includes(method) ){
            throw{
                "info"   : "Unsupported method for axios guarded call",
                "method" : method
            }
        }
        let axiosInstance = Axios.getAxiosInstanceForSymfonyCalls();

        let promise         = axiosInstance[method](url, data);
        let isAuthenticated = await promise.then( response => {
            let isAuthenticated = this.authenticationGuard.checkRouteCallAuthentication(response)
            return isAuthenticated;
        })

        if(isAuthenticated){
            return promise;
        }

        return new Promise(() => {});
    }

    /**
     * @description modify axios itself for working with symfony backend
     *              - include header to make backend recognize call as ajax
     */
    private static getAxiosInstanceForSymfonyCalls()
    {
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        return axios;
    }

}