/**
 * @description represents the same class present in the backend
 */
export default class ParentChildDto {

    private _type     : string = "";
    private _name     : string;
    private _id       : number;
    private _depth    : number;
    private _children : Array<ParentChildDto>;

    get type(): string {
        return this._type;
    }

    set type(value: string) {
        this._type = value;
    }

    get name(): string {
        return this._name;
    }

    set name(value: string) {
        this._name = value;
    }

    get id(): number {
        return this._id;
    }

    set id(value: number) {
        this._id = value;
    }

    get depth(): number {
        return this._depth;
    }

    set depth(value: number) {
        this._depth = value;
    }

    get children(): Array<ParentChildDto> {
        return this._children;
    }

    set children(value: Array<ParentChildDto>) {
        this._children = value;
    }

    /**
     * @description will create object from the json (delivered from response)
     */
    public static fromObject(object: any): ParentChildDto
    {
        let dto      = new ParentChildDto();
        let children = [];

        dto.type     = object.type;
        dto.name     = object.name;
        dto.id       = object.id;
        dto.depth    = object.depth;

        if( undefined === object.children ){
            children = [];
        }else{
            for(let childObject of object.children){
                children.push(ParentChildDto.fromObject(childObject))
            }
        }

        dto.children = children;

        return dto;
    }

    /**
     * @description creates BaseApiDto from axios response
     */
    public static fromAxiosResponse(response: object): ParentChildDto
    {
        //@ts-ignore
        let json = JSON.stringify(response.data);
        let dto  = ParentChildDto.fromObject(json);

        return dto;
    }
}