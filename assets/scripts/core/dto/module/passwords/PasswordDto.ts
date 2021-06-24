import BaseDto from "../../BaseDto";

/**
 * @description this class represents the password entity from backend
 */
export default class PasswordDto
{
    private _id          : string;
    private _login       : string;
    private _password    : string;
    private _url         : string;
    private _description : string;
    private _groupId     : string;

    get login(): string {
        return this._login;
    }

    set login(value: string) {
        this._login = value;
    }

    get password(): string {
        return this._password;
    }

    set password(value: string) {
        this._password = value;
    }

    get url(): string {
        return this._url;
    }

    set url(value: string) {
        this._url = value;
    }

    get description(): string {
        return this._description;
    }

    set description(value: string) {
        this._description = value;
    }

    get groupId(): string {
        return this._groupId;
    }

    set groupId(value: string) {
        this._groupId = value;
    }

    get id(): string {
        return this._id;
    }

    set id(value: string) {
        this._id = value;
    }

    /**
     * @description will create password dto from json
     */
    public static fromJson(json: string): PasswordDto
    {
        let dto = new PasswordDto();

        try{
            var object = JSON.parse(json);
        }catch(Exception){
            BaseDto.throwJsonParsingError('DecryptedPasswordDto', Exception, json);
        }

        dto.login       = object.login;
        dto.password    = object.password;
        dto.url         = object.url;
        dto.description = object.description;
        dto.groupId     = object.groupId;
        dto.id          = object.id;

        return dto;
    }
}