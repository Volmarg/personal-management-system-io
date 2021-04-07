/**
 * @description this is a base dto used as a base for each internal api call response
 */
import BaseInternalApiResponseDto from "../../BaseInternalApiResponseDto";

export default class GetNotesForCategoryResponseDto extends BaseInternalApiResponseDto {

    private _notesJsons : Array<any> = [];

    get notesJsons(): Array<any> {
        return this._notesJsons;
    }

    set notesJsons(value: Array<any>) {
        this._notesJsons = value;
    }

    /**
     * Will build frontend response from backend response
     *
     */
    public static fromJson(json: string): GetNotesForCategoryResponseDto
    {
        let dto = new GetNotesForCategoryResponseDto();

        try{
            var object = JSON.parse(json);
        }catch(Exception){
            throw{
                "message"   : "Could not parse the json for: GetNotesForCategoryResponseDto",
                "exception" : Exception,
                "json"      : json,
            }
        }

        dto.success    = object.success;
        dto.message    = object.message;
        dto.code       = object.code;
        dto.notesJsons = object.notesJsons;

        if( "undefined" !== typeof object.invalidFields ){
            dto.invalidFields = JSON.parse(object.invalidFields);
        }

        return dto;
    }

    /**
     * @description creates BaseInternalApiResponseDto from axios response
     */
    public static fromAxiosResponse(response: object): GetNotesForCategoryResponseDto
    {
        //@ts-ignore
        let json = JSON.stringify(response.data);
        let dto  = GetNotesForCategoryResponseDto.fromJson(json);

        return dto;
    }
}