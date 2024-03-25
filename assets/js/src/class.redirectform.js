import {SmartErrors} from "./class.smarterrors.js?v=1.0.1";

export class RedirectForm{
    /**
     *
     * @param {HTMLFormElement} form
     */
    constructor(form) {
        this.form = form;
        this.inputs = form.querySelectorAll("input[data-url]");
        this.errorMessage = form.querySelector(".error-message")
        this.init();
    }
    init(){
        this.form.addEventListener("submit", (e)=>{
            e.preventDefault();
            try {
                this.doRequest();
            }catch (e){
                console.error(e.message);
            }
        })
        this.inputs.forEach(input=>{
            input.addEventListener("input", ()=>{
                input.value = decodeURI(input.value);
                this.urlValidation(input);
            })
            this.urlValidation(input);
        })
    }

    /**
     *
     * @param {HTMLInputElement} input
     */
    //input URL validation
    urlValidation(input){
        const url = new RegExp(/(https:\/\/www\.|http:\/\/www\.|https:\/\/|http:\/\/)?[a-zA-Z0-9]{2,}(\.[a-zA-Z0-9]{2,})(\.[a-zA-Z0-9]{2,})?/)
        if(input.value.length && !url.test(input.value)){
            input.classList.add("url-error");
        }else{
            input.classList.remove("url-error");
        }
    }
    //Ajax requests for custom redirect form
    doRequest(){
        this.errorMessage.innerHTML = "";
        const xml = new XMLHttpRequest();
        const data = new FormData(this.form);
        this.inputs.forEach(input=>{
            if(!input.value.length){
                input.classList.add("error");
            }
        })
        data.append("action", "sm404_custom_redirect_link");
        xml.open("POST", ajaxurl);
        xml.send(data);
        xml.onreadystatechange = ()=>{
            if(xml.readyState === 4){
                if(xml.status === 200){
                    if(xml.responseText === ""){
                        this.inputs.forEach(input=>{
                            input.value = "";
                        })
                        this.errorMessage.innerHTML = "";
                        this.form.closest(".popup-body").classList.remove("active");
                        window.location.reload();
                    }else{
                        this.errorMessage.innerHTML = xml.responseText;
                    }
                }else{

                }
            }
        }

    }
}