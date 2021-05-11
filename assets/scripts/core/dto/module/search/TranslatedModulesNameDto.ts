import BaseApiDto from "../../BaseApiDto";

export default class TranslatedModulesNameDto extends BaseApiDto
{
    private _modulesNames: Object;

    get modulesNames(): Object {
        return this._modulesNames;
    }

    set modulesNames(value: Object) {
        this._modulesNames = value;
    }

    /**
     * @description Create LoggedInUserDataDto from json
     */
    public static fromJson(json: string): TranslatedModulesNameDto
    {
        let translatedModulesNameDto = new TranslatedModulesNameDto();
        translatedModulesNameDto.prefillBaseFields(json);

        try{
            var object = JSON.parse(json);
        }catch(Exception){
            throw{
                "message"   : "Could not parse json to object for TranslatedModulesNameDto",
                "exception" : Exception
            }
        }

        translatedModulesNameDto.modulesNames = object.modulesNames;
        return translatedModulesNameDto;
    }

    /**
     * @description build LoggedInUserDataDto from axios response object
     */
    public static fromAxiosResponse(axiosResponse: object): TranslatedModulesNameDto
    {
        //@ts-ignore
        let json = JSON.stringify(axiosResponse.data);
        let dto  = this.fromJson(json);

        return dto;
    }
}