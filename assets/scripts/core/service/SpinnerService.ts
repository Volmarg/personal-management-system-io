/**
 * @description handles showing/hiding spinner
 *              this is a bit dirty solution, as the vue controlled DOM should not be manipulated
 *              in other places, this is however quick way to handle the spinner without need
 *              to emit the events
 */
export default class SpinnerService
{

    static readonly SPINNER_TOP_WRAPPER_SELECTOR_ID_NAME = "spinnerTopWrapper";

    /**
     * @description will show spinner
     */
    public static showSpinner(): void
    {
        let spinner = document.getElementById(SpinnerService.SPINNER_TOP_WRAPPER_SELECTOR_ID_NAME);
        spinner.classList.remove('d-none');
    }

    /**
     * @description will hide spinner
     */
    public static hideSpinner(): void
    {
        let spinner = document.getElementById(SpinnerService.SPINNER_TOP_WRAPPER_SELECTOR_ID_NAME);
        spinner.classList.add('d-none');
    }

}