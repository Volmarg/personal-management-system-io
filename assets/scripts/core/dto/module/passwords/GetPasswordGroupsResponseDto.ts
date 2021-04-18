/**
 * @description this is a base dto used as a base for each internal api call response
 */
import BaseInternalApiResponseDto from "../../BaseInternalApiResponseDto";

export default class GetPasswordGroupsResponseDto extends BaseInternalApiResponseDto {

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
    public static fromJson(json: string): GetPasswordGroupsResponseDto
    {
        let dto = new GetPasswordGroupsResponseDto();
        dto.prefillBaseFields(json);

        try{
            var object = JSON.parse(json);
        }catch(Exception){
            throw{
                "message"   : "Could not parse the json for: GetNotesForCategoryResponseDto",
                "exception" : Exception,
                "json"      : json,
            }
        }

        dto.passwordsGroupsJsons = object.passwordsGroups;
        return dto;
    }

    /**
     * @description creates BaseInternalApiResponseDto from axios response
     */
    public static fromAxiosResponse(response: object): GetPasswordGroupsResponseDto
    {
        //@ts-ignore
        let json = JSON.stringify(response.data);
        let dto  = GetPasswordGroupsResponseDto.fromJson(json);

        return dto;
    }
}