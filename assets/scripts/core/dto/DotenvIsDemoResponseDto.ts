import BaseApiDto from "./BaseApiDto";
import BaseDto    from "./BaseDto";

/**
 * @description response used to fetch the .env APP_DEMO state
 */
export default class DotenvIsDemoResponseDto extends BaseApiDto
{
    private _isDemo: boolean;

    get isDemo(): boolean {
        return this._isDemo;
    }

    set isDemo(value: boolean) {
        this._isDemo = value;
    }

    /**
     * @description Create DotenvIsDemoResponseDto from json
     */
    public static fromJson(json: string): DotenvIsDemoResponseDto
    {
        let loggedInUserDataDto = new DotenvIsDemoResponseDto();
        loggedInUserDataDto.prefillBaseFields(json);

        try{
            var object = JSON.parse(json);
        }catch(Exception){
            BaseDto.throwJsonParsingError('DotenvIsDemoDto', Exception, json);
        }

        loggedInUserDataDto.isDemo = object.isDemo;

        return loggedInUserDataDto;
    }

    /**
     * @description build DotenvIsDemoResponseDto from axios response object
     */
    public static fromAxiosResponse(axiosResponse: object): DotenvIsDemoResponseDto
    {
        //@ts-ignore
        let json = JSON.stringify(axiosResponse.data);
        let dto  = this.fromJson(json);

        return dto;
    }
}