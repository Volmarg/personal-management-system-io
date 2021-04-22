import Translations                                 from './../../../../translations/frontend/messages.json';
import {VUE_APP_DEFAULT_STRING_BEFORE_TRANSLATING}  from "../../env";
import JsonPath                                     from 'jsonpath';

/**
 * todo: pass translations Service the same way to vue as it's done with AxiosCsrf
 * @description loads the translation from the translation file
 */
export default class TranslationsService {

    /**
     * @description will return translation for given string from translation files
     *              function searches for translations in form of `zzz.xxx.ccc.dd` in entire file
     *              returns only the first match, so duplications should be ignored and should not raise any error
     */
    public getTranslationForString(searchedTranslationString: string, replacedStrings: Object = {}): string
    {
        let foundValueArray = JsonPath.query(Translations, searchedTranslationString);
        let foundValue      = foundValueArray[0];

        if( "undefined" === typeof foundValue ){
            return VUE_APP_DEFAULT_STRING_BEFORE_TRANSLATING;
        }else{
            let replacedValue = this.replaceParametersInTranslationString(foundValue, replacedStrings);
            return replacedValue;
        }
    }

    /**
     * @see getTranslationForString - does the same but for multiple strings
     */
    public getTranslationsForStrings(searchedTranslationStrings: Array<string>, replacedStrings: Object = {}): Array<string>
    {
        let arrayOfTranslations = [];
        searchedTranslationStrings.forEach( (searchedTranslationString, index) => {
            let foundTranslation = this.getTranslationForString(searchedTranslationString);
            foundTranslation     = this.replaceParametersInTranslationString(foundTranslation, replacedStrings);
            arrayOfTranslations.push(foundTranslation);
        })

        return arrayOfTranslations;
    }

    /**
     * @description this method will simply replace passed string (similar solution to how symfony deals with params in yaml)
     */
    private replaceParametersInTranslationString(translatedString: string, replacedStrings: Object): string
    {
        let searchedStrings = Object.keys(replacedStrings);
        for(let searchedString of searchedStrings){
            let newValue     = replacedStrings[searchedString];
            translatedString = translatedString.replace(searchedString, newValue);
        }

        return translatedString;
    }
}