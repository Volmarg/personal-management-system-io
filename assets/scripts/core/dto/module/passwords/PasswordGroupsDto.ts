/**
 * @description this is a base dto used as a base for each internal api call response
 */
import BaseApiDto from "../../BaseApiDto";
import BaseDto    from "../../BaseDto";

export default class PasswordGroupsDto extends BaseApiDto {

    private _passwordsGroupsJsons : Array<string> = [];

    get passwordsGroupsJsons(): Array<string> {
        return this._passwordsGroupsJsons;
    }

    set passwordsGroupsJsons(value: Array<string>) {
        this._passwordsGroupsJsons = value;
    }

    /**
     * @description will build frontend response from backend response
     */
    public static fromJson(json: string): PasswordGroupsDto
    {
        let dto = new PasswordGroupsDto();
        dto.prefillBaseFields(json);

        try{
            var object = JSON.parse(json);
        }catch(Exception){
            BaseDto.throwJsonParsingError('GetNotesForCategoryResponseDto', Exception, json);
        }

        dto.passwordsGroupsJsons = object.passwordsGroups;
        return dto;
    }

    /**
     * @description creates BaseApiDto from axios response
     */
    public static fromAxiosResponse(response: object): PasswordGroupsDto
    {
        //@ts-ignore
        let json = JSON.stringify(response.data);
        let dto  = PasswordGroupsDto.fromJson(json);

        return dto;
    }
}