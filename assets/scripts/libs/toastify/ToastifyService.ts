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
    private static readonly TOAST_NOTIFICATION_DEFAULT_DURATION = 2000;

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
     * Will show orange notification
     *
     * @param message
     * @param hideAfterTime
     */
    public static showOrangeNotification(message: string, hideAfterTime:  boolean = true)
    {
        this.buildToastifyInstance("orange", message, hideAfterTime);
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
    private static buildToastifyInstance(backgroundColor: string, message: string, hideAfterTime: boolean = true): void
    {
        let targetWrapperToMountInto = document.querySelector(`.${ToastifyService.TOAST_NOTIFICATION_WRAPPER_CLASS_NAME}`);

       let toastInstance = Toastify({
            text            : message,
            gravity         : "top",
            position        : "center",
            backgroundColor : backgroundColor,
            className       : "toastify-instance",
            stopOnFocus     : true,
            selector        : targetWrapperToMountInto,
            duration        : ToastifyService.TOAST_NOTIFICATION_DEFAULT_DURATION,
        });

       if(!hideAfterTime){
           toastInstance.options.duration = -1; // permanent
       }

       toastInstance.showToast();

       // onClick must be handled this way otherwise the `toastElement` is not available inside of callback body
        toastInstance.options.onClick = () => {
            toastInstance.toastElement.remove()
        }
    }


}