import BaseApiDto from "../../BaseApiDto";
import BaseDto    from "../../BaseDto";

/**
 * @description fronted representation of the GetDecryptedPasswordResponseDTO
 *              contains decrypted password
 */
export default class DecryptedPasswordDto extends BaseApiDto
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
    public static fromJson(json: string): DecryptedPasswordDto
    {
        let dto = new DecryptedPasswordDto();
        dto.prefillBaseFields(json);

        try{
            var object = JSON.parse(json);
        }catch(Exception){
            BaseDto.throwJsonParsingError('DecryptedPasswordDto', Exception, json);
        }

        dto.decryptedPassword = object.decryptedPassword;
        return dto;
    }

    /**
     * @description creates GetDecryptedPasswordResponseDto from axios response
     */
    public static fromAxiosResponse(response: object): DecryptedPasswordDto
    {
        //@ts-ignore
        let json = JSON.stringify(response.data);
        let dto  = DecryptedPasswordDto.fromJson(json);

        return dto;
    }

}