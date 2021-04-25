import TranslationsService from "../../scripts/core/service/TranslationsService";

/**
 * @description this plugin handles the translations for Vue.js the same way that symfony does via (trans).
 *              There is no support to language switch with current implementation.
 */
export default class TranslationPlugin
{
    /**
     * @description will return translation for given string from translation files
     *              function searches for translations in form of `zzz.xxx.ccc.dd` in entire file
     *              returns only the first match, so duplications should be ignored and should not raise any error
     */
    public static trans(searchedTranslationString: string, replacedStrings: Object = {}): string
    {
        let translationService = new TranslationsService();
        return translationService.getTranslationForString(searchedTranslationString, replacedStrings);
    }

}