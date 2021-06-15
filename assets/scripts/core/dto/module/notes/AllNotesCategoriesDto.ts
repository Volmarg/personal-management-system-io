/**
 * @description this is a base dto used as a base for each internal api call response
 */
import BaseApiDto from "../../BaseApiDto";

export default class AllNotesCategoriesDto extends BaseApiDto {

    private _notesCategoriesJsons : Array<any> = [];

    get notesCategoriesJsons(): Array<any> {
        return this._notesCategoriesJsons;
    }

    set notesCategoriesJsons(value: Array<any>) {
        this._notesCategoriesJsons = value;
    }

    /**
     * @description Will build frontend response from backend response
     */
    public static fromJson(json: string): AllNotesCategoriesDto
    {
        let dto = new AllNotesCategoriesDto();
        dto.prefillBaseFields(json);

        try{
            var object = JSON.parse(json);
        }catch(Exception){
            throw{
                "message"   : "Could not parse the json for: AllNotesCategoriesDto",
                "exception" : Exception,
                "json"      : json,
            }
        }

        dto.notesCategoriesJsons = object.notesCategoriesJsons;

        return dto;
    }

    /**
     * @description creates AllNotesCategoriesDto from axios response
     */
    public static fromAxiosResponse(response: object): AllNotesCategoriesDto
    {
        //@ts-ignore
        let json = JSON.stringify(response.data);
        let dto  = AllNotesCategoriesDto.fromJson(json);

        return dto;
    }
}