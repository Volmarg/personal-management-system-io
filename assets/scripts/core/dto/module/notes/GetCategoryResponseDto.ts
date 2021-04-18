/**
 * @description this is a base dto used as a base for each internal api call response
 */
import BaseInternalApiResponseDto from "../../BaseInternalApiResponseDto";

export default class GetCategoryResponseDto extends BaseInternalApiResponseDto {

    private _notesJsons : Array<any> = [];
    private _name        : string     = "";

    get notesJsons(): Array<any> {
        return this._notesJsons;
    }

    set notesJsons(value: Array<any>) {
        this._notesJsons = value;
    }

    get name(): string {
        return this._name;
    }

    set name(value: string) {
        this._name = value;
    }

    /**
     * Will build frontend response from backend response
     *
     */
    public static fromJson(json: string): GetCategoryResponseDto
    {
        let dto = new GetCategoryResponseDto();
        dto.prefillBaseFields(json);

        try{
            var object = JSON.parse(json);
        }catch(Exception){
            throw{
                "message"   : "Could not parse the json for: GetCategoryResponseDto",
                "exception" : Exception,
                "json"      : json,
            }
        }

        dto.notesJsons = object.notesJsons;
        dto.name      = object.name;

        return dto;
    }

    /**
     * @description creates BaseInternalApiResponseDto from axios response
     */
    public static fromAxiosResponse(response: object): GetCategoryResponseDto
    {
        //@ts-ignore
        let json = JSON.stringify(response.data);
        let dto  = GetCategoryResponseDto.fromJson(json);

        return dto;
    }
}