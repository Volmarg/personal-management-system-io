/**
 * @description contains basic information about password group and the passwords in form of json
 *              - the returned password is encoded
 */
import BaseInternalApiResponseDto from "../../BaseInternalApiResponseDto";

export default class GetPasswordGroupWithPasswordsResponseDTO extends BaseInternalApiResponseDto
{
    private _passwordGroupId   : string;
    private _passwordGroupName : string;
    private _passwordsJsons    : Array<string>;

    get passwordGroupId(): string {
        return this._passwordGroupId;
    }

    set passwordGroupId(value: string) {
        this._passwordGroupId = value;
    }

    get passwordGroupName(): string {
        return this._passwordGroupName;
    }

    set passwordGroupName(value: string) {
        this._passwordGroupName = value;
    }

    get passwordsJsons(): Array<string> {
        return this._passwordsJsons;
    }

    set passwordsJsons(value: Array<string>) {
        this._passwordsJsons = value;
    }

    /**
     * @description will build frontend response from backend response
     */
    public static fromJson(json: string): GetPasswordGroupWithPasswordsResponseDTO
    {
        let dto = new GetPasswordGroupWithPasswordsResponseDTO();
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

        dto.passwordsJsons    = object.passwordsJsons;
        dto.passwordGroupName = object.passwordGroupName;
        dto.passwordGroupId   = object.passwordGroupId;
        return dto;
    }

    /**
     * @description creates BaseInternalApiResponseDto from axios response
     */
    public static fromAxiosResponse(response: object): GetPasswordGroupWithPasswordsResponseDTO
    {
        //@ts-ignore
        let json = JSON.stringify(response.data);
        let dto  = GetPasswordGroupWithPasswordsResponseDTO.fromJson(json);

        return dto;
    }
}