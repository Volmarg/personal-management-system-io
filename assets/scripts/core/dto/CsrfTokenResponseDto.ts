import BaseInternalApiResponseDto from "./BaseInternalApiResponseDto";

/**
 * @description response used to fetch the csrf token for form submission
 */
export default class CsrfTokenResponseDto extends BaseInternalApiResponseDto
{
    private _csrToken: string;

    get csrToken(): string {
        return this._csrToken;
    }

    set csrToken(value: string) {
        this._csrToken = value;
    }

    /**
     * @description returns current dto as string
     */
    public toJson(): string
    {
        let object = {
            csrfToken : this.csrToken,
            success   : this.success,
            code      : this.code,
            message   : this.message
        }

        return JSON.stringify(object);
    }

    /**
     * @description Create LoggedInUserDataDto from json
     */
    public static fromJson(json: string): CsrfTokenResponseDto
    {
        let loggedInUserDataDto = new CsrfTokenResponseDto();
        loggedInUserDataDto.prefillBaseFields(json);

        try{
            var object = JSON.parse(json);
        }catch(Exception){
            throw{
                "message"   : "Could not parse json to object for CsrfTokenResponseDto",
                "exception" : Exception
            }
        }

        loggedInUserDataDto.csrToken = object.csrToken;
        return loggedInUserDataDto;
    }

    /**
     * @description build LoggedInUserDataDto from axios response object
     */
    public static fromAxiosResponse(axiosResponse: object): CsrfTokenResponseDto
    {
        //@ts-ignore
        let json = JSON.stringify(axiosResponse.data);
        let dto  = this.fromJson(json);

        return dto;
    }
}