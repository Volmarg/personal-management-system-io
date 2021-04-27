import BaseApiDto from "./BaseApiDto";

/**
 * @description response used to fetch the csrf token for form submission
 */
export default class CsrfTokenDto extends BaseApiDto
{
    private _csrToken: string;

    get csrToken(): string {
        return this._csrToken;
    }

    set csrToken(value: string) {
        this._csrToken = value;
    }

    /**
     * @description Create LoggedInUserDataDto from json
     */
    public static fromJson(json: string): CsrfTokenDto
    {
        let loggedInUserDataDto = new CsrfTokenDto();
        loggedInUserDataDto.prefillBaseFields(json);

        try{
            var object = JSON.parse(json);
        }catch(Exception){
            throw{
                "message"   : "Could not parse json to object for CsrfTokenDto",
                "exception" : Exception
            }
        }

        loggedInUserDataDto.csrToken = object.csrToken;
        return loggedInUserDataDto;
    }

    /**
     * @description build LoggedInUserDataDto from axios response object
     */
    public static fromAxiosResponse(axiosResponse: object): CsrfTokenDto
    {
        //@ts-ignore
        let json = JSON.stringify(axiosResponse.data);
        let dto  = this.fromJson(json);

        return dto;
    }
}