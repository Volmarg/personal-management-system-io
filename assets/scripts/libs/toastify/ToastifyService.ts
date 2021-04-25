import Toastify from 'toastify-js'
import "toastify-js/src/toastify.css"

/**
 * @description handles showing notifications
 *
 * @link https://github.com/apvarun/toastify-js/blob/master/README.md
 * @link https://apvarun.github.io/toastify-js/#
 */
export default class ToastifyService
{
    static readonly TOAST_NOTIFICATION_WRAPPER_CLASS_NAME = "toast-notification-wrapper";

    /**
     * Will show green notification
     *
     * @param message
     */
    public static showRedNotification(message: string)
    {
        this.buildToastifyInstance("red", message);
    }

    /**
     * Will show green notification
     *
     * @param message
     */
    public static showGreenNotification(message: string)
    {
        this.buildToastifyInstance("green", message);
    }

    /**
     * @description build tostify instance
     */
    private static buildToastifyInstance(backgroundColor: string, message: string): void
    {
        let targetWrapperToMountInto = document.querySelector(`.${ToastifyService.TOAST_NOTIFICATION_WRAPPER_CLASS_NAME}`);

       let toastInstance = Toastify({
            text            : message,
            duration        : 2000,
            gravity         : "top",
            position        : "center",
            backgroundColor : backgroundColor,
            className       : "toastify-instance",
            stopOnFocus     : true,
            selector        : targetWrapperToMountInto
        }).showToast();

       // onClick must be handled this way otherwise the `toastElement` is not available inside of callback body
        toastInstance.options.onClick = () => {
            toastInstance.toastElement.remove()
        }
    }


}