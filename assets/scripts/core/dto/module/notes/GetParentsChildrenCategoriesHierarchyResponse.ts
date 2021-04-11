import BaseInternalApiResponseDto from "../../BaseInternalApiResponseDto";

/**
 * @description represents the same class present in the backend
 */
export default class GetParentsChildrenCategoriesHierarchyResponse extends BaseInternalApiResponseDto {

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
    public static fromJson(json: string): GetParentsChildrenCategoriesHierarchyResponse
    {
        let dto = new GetParentsChildrenCategoriesHierarchyResponse();

        try{
            var object = JSON.parse(json);
        }catch(Exception){
            throw{
                "message"   : "Could not parse the json for: GetParentsChildrenCategoriesHierarchyResponse",
                "exception" : Exception,
                "json"      : json,
            }
        }

        dto.success    = object.success;
        dto.message    = object.message;
        dto.code       = object.code;
        dto._hierarchy = object.hierarchy

        if( "undefined" !== typeof object.invalidFields ){
            dto.invalidFields = JSON.parse(object.invalidFields);
        }

        return dto;
    }

    /**
     * @description creates BaseInternalApiResponseDto from axios response
     */
    public static fromAxiosResponse(response: object): GetParentsChildrenCategoriesHierarchyResponse
    {
        //@ts-ignore
        let json = JSON.stringify(response.data);
        let dto  = GetParentsChildrenCategoriesHierarchyResponse.fromJson(json);

        return dto;
    }
}
