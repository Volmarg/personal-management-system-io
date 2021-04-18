/**
 * @description contains usefull methods to working with the strings
 */
export default class StringUtils {

    /**
     * @description Will check if given string is empty
     *
     * @param string
     * @return boolean
     */
    public static isEmptyString(string: string): boolean
    {
        return (
            ""          == string
            ||  null        == string
            ||  "null"      == string
            ||  undefined   == string
            ||  "undefined" == undefined
            ||  "undefined" == typeof string
        );
    }

    /**
     * @description Check if both strings are the same
     * @param firstString
     * @param secondString
     */
    public static areTheSame(firstString: string, secondString: string): boolean
    {
        return firstString === secondString;
    }

    /**
     * @description will check if given string is longer than provided maxCharactersCount
     *              - if yes then the string length will be reduced and dots are added on the end
     */
    public static substringAndAddDots(string: string, maxCharactersCount: number): string
    {
        if( string.length > maxCharactersCount ){
            string = string.substr(0, maxCharactersCount) + '...';
        }

        return string;
    }

}