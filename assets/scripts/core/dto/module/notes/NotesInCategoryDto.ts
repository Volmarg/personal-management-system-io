/**
 * @description this is a base dto used as a base for each internal api call response
 */
import BaseApiDto from "../../BaseApiDto";

export default class NotesInCategoryDto extends BaseApiDto {

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
    public static fromJson(json: string): NotesInCategoryDto
    {
        let dto = new NotesInCategoryDto();
        dto.prefillBaseFields(json);

        try{
            var object = JSON.parse(json);
        }catch(Exception){
            throw{
                "message"   : "Could not parse the json for: NotesInCategoryDto",
                "exception" : Exception,
                "json"      : json,
            }
        }

        dto.notesJsons = object.notesJsons;
        dto.name      = object.name;

        return dto;
    }

    /**
     * @description creates BaseApiDto from axios response
     */
    public static fromAxiosResponse(response: object): NotesInCategoryDto
    {
        //@ts-ignore
        let json = JSON.stringify(response.data);
        let dto  = NotesInCategoryDto.fromJson(json);

        return dto;
    }
}