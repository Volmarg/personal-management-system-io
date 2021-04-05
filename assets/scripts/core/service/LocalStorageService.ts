import StringUtils          from "../utils/StringUtils";
import LoggedInUserDataDto  from "../dto/LoggedInUserDataDto";

export default class LocalStorageService {

    static readonly SESSION_KEY_LOGGED_USER    = "loggedUser";
    static readonly SESSION_KEY_IS_FIRST_LOGIN = "firstLogin";

    /**
     * @description will set the logged in user dto in local storage
     */
    public setLoggedInUser(loggedInUserDataDto: LoggedInUserDataDto): void
    {
        localStorage.setItem(LocalStorageService.SESSION_KEY_LOGGED_USER, loggedInUserDataDto.toJson());
    }

    /**
     * @description will get the logged in user dto from local storage or null if it's not set
     */
    public getLoggedInUser(): LoggedInUserDataDto | null
    {
        let json = localStorage.getItem(LocalStorageService.SESSION_KEY_LOGGED_USER);

        if( !StringUtils.isEmptyString(json) ){
            let dto = LoggedInUserDataDto.fromJson(json);
            return dto;
        }

        return null;
    }

    /**
     * @description will check if the logged in user data is set in local storage
     */
    public isLoggedInUserSet(): boolean
    {
        let json = localStorage.getItem(LocalStorageService.SESSION_KEY_LOGGED_USER);
        return !StringUtils.isEmptyString(json)
    }

    /**
     * @description will check if this is the first time that user logs in
     */
    public isFirstLogin(): boolean
    {
        let isFirstLogin = localStorage.getItem(LocalStorageService.SESSION_KEY_IS_FIRST_LOGIN);
        return StringUtils.isEmptyString(isFirstLogin);
    }

    /**
     * @description will set the first login to true
     */
    public setFirstLogin(): void {
        localStorage.setItem(LocalStorageService.SESSION_KEY_IS_FIRST_LOGIN, 'true');
    }

}