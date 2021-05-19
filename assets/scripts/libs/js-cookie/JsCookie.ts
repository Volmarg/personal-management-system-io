import * as Cookies from 'js-cookie';
import StringUtils  from "../../core/utils/StringUtils";

/**
 * @description handles the predefined cookies
 */
export default class JsCookie {

    static readonly COOKIE_KEY_JS_SETTINGS_SELECTED_THEME   = "JS_SETTINGS_SELECTED_THEME";

    /**
     * @description save the selected theme
     */
    public static setJsSettingsSelectedTheme(themName: string): void
    {
        Cookies.set(JsCookie.COOKIE_KEY_JS_SETTINGS_SELECTED_THEME, themName);
    }

    /**
     * @description get the selected theme
     *
     * @return boolean
     */
    public static getJsSettingsSelectedTheme(): string
    {
        let cookieValue = Cookies.get(JsCookie.COOKIE_KEY_JS_SETTINGS_SELECTED_THEME);
        return cookieValue;
    }

    /**
     * @description checks if the theme is selected already
     *
     * @return boolean
     */
    public static isJsSettingsSelectedTheme(): boolean
    {
        let cookieValue = Cookies.get(JsCookie.COOKIE_KEY_JS_SETTINGS_SELECTED_THEME);
        return !StringUtils.isEmptyString(cookieValue);
    }

}