export class SmartErrors{
    /**
     *
     * @param {string} message
     */
    constructor(message) {
        this.message = message;
    }
    setError(){
        console.error(this.message)
    }
}