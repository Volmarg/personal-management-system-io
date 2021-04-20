import BaseInternalApiResponseDto from "../../BaseInternalApiResponseDto";

/**
 * @description fronted representation of the GetDecryptedPasswordResponseDTO
 *              contains decrypted password
 */
export default class GetDecryptedPasswordResponseDto extends BaseInternalApiResponseDto
{

    private _decryptedPassword: string;


    get decryptedPassword(): string {
        return this._decryptedPassword;
    }

    set decryptedPassword(value: string) {
        this._decryptedPassword = value;
    }

    /**
     * @description will build frontend response from backend response
     */
    public static fromJson(json: string): GetDecryptedPasswordResponseDto
    {
        let dto = new GetDecryptedPasswordResponseDto();
        dto.prefillBaseFields(json);

        try{
            var object = JSON.parse(json);
        }catch(Exception){
            throw{
                "message"   : "Could not parse the json for: GetDecryptedPasswordResponseDto",
                "exception" : Exception,
                "json"      : json,
            }
        }

        dto.decryptedPassword = object.decryptedPassword;
        return dto;
    }

    /**
     * @description creates GetDecryptedPasswordResponseDto from axios response
     */
    public static fromAxiosResponse(response: object): GetDecryptedPasswordResponseDto
    {
        //@ts-ignore
        let json = JSON.stringify(response.data);
        let dto  = GetDecryptedPasswordResponseDto.fromJson(json);

        return dto;
    }

}