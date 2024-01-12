export class Sm404settings{
    constructor() {
        this.autoRedirect = document.querySelector(".auto-redirect-switcher .switcher");
        this.init();
    }

    init(){
        this.setAutoRedirect();
    }

    setAutoRedirect(){
        this.autoRedirect.callback = ()=>{
            if(confirm("Do you want change autoredirect status? after changing the status, the page will be reloaded, please save all unchanged datas")){
                const xml = new XMLHttpRequest();
                xml.open("PUT", ajaxurl + "?action=change_auto_redirect");
                xml.send(JSON.stringify({0 : true}));
                xml.onreadystatechange = ()=>{
                    if(xml.readyState === 4 && xml.status === 200){
                        location.reload()
                    }
                }
            }
        }
    }
}