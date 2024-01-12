import {SmartErrors} from "./class.smarterrors.js?v=1.0.1";
import {RedirectForm} from "./class.redirectform.js?v=1.0.1";

export class SmartUrl{
    constructor() {
        this.form = document.querySelector(".popup-body form.redirects-form")
        try{
            this.setAssets()
        }catch(e){

        }
    }
    setAssets(){
        if(!this.form){
            throw new SmartErrors("Form not exsist");
        }
        new RedirectForm(this.form);
    }
}

