import BaseApiDto from "../../BaseApiDto";

/**
 * @description represents the same class present in the backend
 */
export default class ParentsChildrenCategoriesHierarchyDto extends BaseApiDto {

    private _hierarchy: Array<string>;

    get hierarchy(): Array<string> {
        return this._hierarchy;
    }

    set hierarchy(value: Array<string>) {
        this._hierarchy = value;
    }

    /**
     * @description will create object from the json (delivered from response)
     *
     * @param json
     */
    public static fromJson(json: string): ParentsChildrenCategoriesHierarchyDto
    {
        let dto = new ParentsChildrenCategoriesHierarchyDto();
        dto.prefillBaseFields(json);

        try{
            var object = JSON.parse(json);
        }catch(Exception){
            throw{
                "message"   : "Could not parse the json for: ParentsChildrenCategoriesHierarchyDto",
                "exception" : Exception,
                "json"      : json,
            }
        }

        dto._hierarchy = object.hierarchy
        return dto;
    }

    /**
     * @description creates BaseApiDto from axios response
     */
    public static fromAxiosResponse(response: object): ParentsChildrenCategoriesHierarchyDto
    {
        //@ts-ignore
        let json = JSON.stringify(response.data);
        let dto  = ParentsChildrenCategoriesHierarchyDto.fromJson(json);

        return dto;
    }
}
