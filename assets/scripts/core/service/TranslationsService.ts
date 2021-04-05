import Translations                                 from './../../../../translations/frontend/messages.json';
import {VUE_APP_DEFAULT_STRING_BEFORE_TRANSLATING}  from "../../env";
import JsonPath                                     from 'jsonpath';

/**
 * @description loads the translation from the translation file
 */
export default class TranslationsService {

    /**
     * @description will return translation for given string from translation files
     *              function searches for translations in form of `zzz.xxx.ccc.dd` in entire file
     *              returns only the first match, so duplications should be ignored and should not raise any error
     */
    public getTranslationForString(searchedTranslationString: string): string
    {
        let foundValueArray = JsonPath.query(Translations, searchedTranslationString);
        let foundValue      = foundValueArray[0];

        if( "undefined" === typeof foundValue ){
            return VUE_APP_DEFAULT_STRING_BEFORE_TRANSLATING;
        }

        return foundValue;
    }

    /**
     * @see getTranslationForString - does the same but for multiple strings
     */
    public getTranslationsForStrings(searchedTranslationStrings: Array<string>): Array<string>
    {
        let arrayOfTranslations = [];
        searchedTranslationStrings.forEach( (searchedTranslationString, index) => {
            let foundTranslation = this.getTranslationForString(searchedTranslationString);
            arrayOfTranslations.push(foundTranslation);
        })

        return arrayOfTranslations;
    }
}