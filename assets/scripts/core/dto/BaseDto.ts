import PmsError from "../error/PmsError";

/**
 * Base of all dtos
 */
export default class BaseDto {
    /**
     * Will throw error related to json parsing
     */
    public static throwJsonParsingError(className: string, Exception: any, json: string){
        throw new PmsError("Could not parse the json for: " + className, {
            "exception" : Exception,
            "json"      : json,
        })
    }
}