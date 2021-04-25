/**
 * @description handles showing/hiding spinner
 */
export default class SpinnerService
{

    static readonly SPINNER_WRAPPER_SELECTOR_ID_NAME = "spinnerWrapper";

    /**
     * @description will show spinner
     */
    public static showSpinner(): void
    {
        let spinner = document.getElementById(SpinnerService.SPINNER_WRAPPER_SELECTOR_ID_NAME);
        spinner.classList.remove('d-none');
    }

    /**
     * @description will hide spinner
     */
    public static hideSpinner(): void
    {
        let spinner = document.getElementById(SpinnerService.SPINNER_WRAPPER_SELECTOR_ID_NAME);
        spinner.classList.add('d-none');
    }

}