/**
 * @description Represents the note category entity from backend
 */
export default class NoteCategoryDto {

    private _id : number;
    private _icon : string;
    private _note : string;
    private _name : string;
    private _color : string;
    private _parentId : string;

    get id(): number {
        return this._id;
    }

    set id(value: number) {
        this._id = value;
    }

    get icon(): string {
        return this._icon;
    }

    set icon(value: string) {
        this._icon = value;
    }

    get note(): string {
        return this._note;
    }

    set note(value: string) {
        this._note = value;
    }

    get name(): string {
        return this._name;
    }

    set name(value: string) {
        this._name = value;
    }

    get color(): string {
        return this._color;
    }

    set color(value: string) {
        this._color = value;
    }

    get parentId(): string {
        return this._parentId;
    }

    set parentId(value: string) {
        this._parentId = value;
    }


    /**
     * Will build frontend response from backend response
     *
     */
    public static fromJson(json: string): NoteCategoryDto
    {
        let dto = new NoteCategoryDto();

        try{
            var object = JSON.parse(json);
        }catch(Exception){
            throw{
                "message"   : "Could not parse the json for: NoteCategoryDto",
                "exception" : Exception,
                "json"      : json,
            }
        }

        dto.id       = object.id;
        dto.icon     = object.icon;
        dto.note     = object.note;
        dto.name     = object.name;
        dto.color    = object.color;
        dto.parentId = object.parentId;

        return dto;
    }

    /**
     * @description creates NoteCategoryDto from axios response
     */
    public static fromAxiosResponse(response: object): NoteCategoryDto
    {
        //@ts-ignore
        let json = JSON.stringify(response.data);
        let dto  = NoteCategoryDto.fromJson(json);

        return dto;
    }
}