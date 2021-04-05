import BaseInternalApiResponseDto from "./BaseInternalApiResponseDto";

/**
 * @description response used to fetch the .env APP_DEMO state
 */
export default class DotenvIsDemoResponseDto extends BaseInternalApiResponseDto
{
    private _isDemo: boolean;

    get isDemo(): boolean {
        return this._isDemo;
    }

    set isDemo(value: boolean) {
        this._isDemo = value;
    }

    /**
     * @description returns current dto as string
     */
    public toJson(): string
    {
        let object = {
            demo      : this.isDemo,
            success   : this.success,
            code      : this.code,
            message   : this.message
        }

        return JSON.stringify(object);
    }

    /**
     * @description Create DotenvIsDemoResponseDto from json
     */
    public static fromJson(json: string): DotenvIsDemoResponseDto
    {
        let baseDto = super.fromJson(json);

        try{
            var object = JSON.parse(json);
        }catch(Exception){
            throw{
                "message"   : "Could not parse json to object for CsrfTokenResponseDto",
                "exception" : Exception
            }
        }

        let loggedInUserDataDto     = new DotenvIsDemoResponseDto();
        loggedInUserDataDto.success = baseDto.success;
        loggedInUserDataDto.code    = baseDto.code;
        loggedInUserDataDto.message = baseDto.message;
        loggedInUserDataDto.isDemo  = object.isDemo;

        return loggedInUserDataDto;
    }

    /**
     * @description build DotenvIsDemoResponseDto from axios response object
     */
    public static fromAxiosResponse(axiosResponse: object): DotenvIsDemoResponseDto
    {
        //@ts-ignore
        let json = JSON.stringify(axiosResponse.data);
        let dto  = this.fromJson(json);

        return dto;
    }
}