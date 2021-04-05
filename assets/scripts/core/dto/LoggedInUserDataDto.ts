import BaseInternalApiResponseDto from "./BaseInternalApiResponseDto";

/**
 * @description representation of currently logged in user data
 */
export default class LoggedInUserDataDto extends BaseInternalApiResponseDto{
    private _shownName : string = "";
    private _avatar : string = "";

    get shownName(): string {
        return this._shownName;
    }

    set shownName(value: string) {
        this._shownName = value;
    }

    get avatar(): string {
        return this._avatar;
    }

    set avatar(value: string) {
        this._avatar = value;
    }

    /**
     * @description returns current dto as string
     */
    public toJson(): string
    {
        let object = {
            avatar    : this.avatar,
            shownName : this.shownName,
            success   : this.success,
            code      : this.code,
            message   : this.message
        }

        return JSON.stringify(object);
    }

    /**
     * @description Create LoggedInUserDataDto from json
     */
    public static fromJson(json: string): LoggedInUserDataDto
    {
        let baseDto = super.fromJson(json);

        try{
            var object = JSON.parse(json);
        }catch(Exception){
            throw{
                "message"   : "Could not parse json to object for LoggedInUserDataDto",
                "exception" : Exception
            }
        }

        let loggedInUserDataDto       = new LoggedInUserDataDto();
        loggedInUserDataDto.success   = baseDto.success;
        loggedInUserDataDto.code      = baseDto.code;
        loggedInUserDataDto.message   = baseDto.message;
        loggedInUserDataDto.avatar    = object.avatar;
        loggedInUserDataDto.shownName = object.shownName;

        return loggedInUserDataDto;
    }

    /**
     * @description build LoggedInUserDataDto from axios response object
     */
    public static fromAxiosResponse(axiosResponse: object): LoggedInUserDataDto
    {
        //@ts-ignore
        let json = JSON.stringify(axiosResponse.data);
        let dto  = this.fromJson(json);

        return dto;
    }
}