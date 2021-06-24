import BaseDto from "../../BaseDto";

/**
 * @description frontend representation of the Note entity
 */
export default class MyNoteDto extends BaseDto {
    private _id          : number;
    private _title       : string;
    private _body        : string;
    private _categoryId ?: number

    get id(): number {
        return this._id;
    }

    set id(value: number) {
        this._id = value;
    }

    get title(): string {
        return this._title;
    }

    set title(value: string) {
        this._title = value;
    }

    get body(): string {
        return this._body;
    }

    set body(value: string) {
        this._body = value;
    }

    get categoryId(): number {
        return this._categoryId;
    }

    set categoryId(value: number) {
        this._categoryId = value;
    }

    /**
     * @description will create object from the json (delivered from response)
     */
    public static fromJson(json: string): MyNoteDto
    {
        try{
            var object = JSON.parse(json);
        }catch(Exception){
            BaseDto.throwJsonParsingError('MyNoteDto', Exception, json);
        }

        let dto         = new MyNoteDto();

        dto._body       = object.body;
        dto._categoryId = object.categoryId;
        dto._id         = object.id;
        dto._title      = object.title;

        return dto;
    }

    /**
     * @description creates BaseApiDto from axios response
     */
    public static fromAxiosResponse(response: object): MyNoteDto
    {
        //@ts-ignore
        let json = JSON.stringify(response.data);
        let dto  = MyNoteDto.fromJson(json);

        return dto;
    }
}