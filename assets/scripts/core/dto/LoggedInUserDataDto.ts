import BaseApiDto from "./BaseApiDto";

/**
 * @description representation of currently logged in user data
 */
export default class LoggedInUserDataDto extends BaseApiDto{
    private _shownName : string = "";
    private _avatar    : string = "";
    private _loggedIn  : boolean = false;

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

    get loggedIn(): boolean {
        return this._loggedIn;
    }

    set loggedIn(value: boolean) {
        this._loggedIn = value;
    }

    /**
     * @description returns current dto as string
     */
    public toJson(): string
    {
        let object = {
            shownName : this.shownName,
            avatar    : this.avatar,
            loggedIn  : this.loggedIn,
        }

        return JSON.stringify(object);
    }

    /**
     * @description Create LoggedInUserDataDto from json
     */
    public static fromJson(json: string): LoggedInUserDataDto
    {
        let loggedInUserDataDto = new LoggedInUserDataDto();
        loggedInUserDataDto.prefillBaseFields(json);

        try{
            var object = JSON.parse(json);
        }catch(Exception){
            throw{
                "message"   : "Could not parse json to object for LoggedInUserDataDto",
                "exception" : Exception
            }
        }

        loggedInUserDataDto.avatar    = object.avatar;
        loggedInUserDataDto.shownName = object.shownName;
        loggedInUserDataDto.loggedIn  = object.loggedIn;

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