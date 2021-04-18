/**
 * @description this is a base dto used as a base for each internal api call response
 */
export default class BaseInternalApiResponseDto {
    private _code: number;
    private _message: string;
    private _success: boolean;
    private _invalidFields: Array<any>;

    get code(): number {
        return this._code;
    }

    set code(value: number) {
        this._code = value;
    }

    get message(): string {
        return this._message;
    }

    set message(value: string) {
        this._message = value;
    }

    get success(): boolean {
        return this._success;
    }

    set success(value: boolean) {
        this._success = value;
    }

    get invalidFields(): Array<any> {
        return this._invalidFields;
    }

    set invalidFields(value: Array<any>) {
        this._invalidFields = value;
    }

    /**
     * @description will create object from the json (delivered from response)
     *
     * @param json
     */
    public static fromJson(json: string): BaseInternalApiResponseDto
    {
        let dto = new BaseInternalApiResponseDto();
        dto.prefillBaseFields(json);

        return dto;
    }

    /**
     * @description creates BaseInternalApiResponseDto from axios response
     */
    public static fromAxiosResponse(response: object): BaseInternalApiResponseDto
    {
        //@ts-ignore
        let json = JSON.stringify(response.data);
        let dto  = BaseInternalApiResponseDto.fromJson(json);

        return dto;
    }

    /**
     * @description will prefill the fields of current calling class which extends from this one,
     *              this reduced the necessity of setting the base fields in child class
     */
    protected prefillBaseFields(json: string): this
    {
        try{
            var object = JSON.parse(json);
        }catch(Exception){
            throw{
                "message"   : "Could not parse the json for: BaseInternalApiResponseDto",
                "exception" : Exception,
                "json"      : json,
            }
        }

        this.success = object.success;
        this.message = object.message;
        this.code    = object.code;

        if( "undefined" !== typeof object.invalidFields ){
            this.invalidFields = JSON.parse(object.invalidFields);
        }

        return this;
    }
}