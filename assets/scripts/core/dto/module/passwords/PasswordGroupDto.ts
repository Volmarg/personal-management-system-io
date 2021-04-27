/**
 * @description this a representation of the backend entity PasswordGroup
 *
 */
export default class PasswordGroupDto {

    private _id   : number;
    private _name : string;

    get id(): number {
        return this._id;
    }

    set id(value: number) {
        this._id = value;
    }

    get name(): string {
        return this._name;
    }

    set name(value: string) {
        this._name = value;
    }

    /**
     * @description will create object from the json (delivered from response)
     *
     * @param json
     */
    public static fromJson(json: string): PasswordGroupDto
    {
        let dto = new PasswordGroupDto();

        try{
            var object = JSON.parse(json);
        }catch(Exception){
            throw{
                "message"   : "Could not parse the json for: PasswordGroupDto",
                "exception" : Exception,
                "json"      : json,
            }
        }

        dto.id   = object.id;
        dto.name = object.name;

        return dto;
    }

    /**
     * @description creates BaseApiDto from axios response
     */
    public static fromAxiosResponse(response: object): PasswordGroupDto
    {
        //@ts-ignore
        let json = JSON.stringify(response.data);
        let dto  = PasswordGroupDto.fromJson(json);

        return dto;
    }

}