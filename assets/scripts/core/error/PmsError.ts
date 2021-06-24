export default class PmsError extends Error {

    private _data: any;

    get data(): any {
        return this._data;
    }

    set data(value: any) {
        this._data = value;
    }

    public constructor(message: string, data: any = "")
    {
        let stringData  = "";
        let usedMessage = message;
        if( "" !== data ){
            stringData  = "\n\n" + JSON.stringify(data, null, 2) + "\n\n";
            usedMessage = message + stringData;
        }

        super(usedMessage);
    }
}