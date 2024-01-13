import {SmartUrl} from "./class-smarturl.js?v=1.0.1";
import {Smart404_chart} from "./smart404_chart.js?v=1.0.1";
import {Sm404settings} from "./sm404settings.js?v=1.0.1";
import {Smart404_datatables} from "./smart404_datatables.js?v=1.0.1";
import {Smart404_postTypes} from "./smart404_postTypes.js?v=1.0.1";

//Initializing charts
function initCharts(){
    document.querySelectorAll(".graphics-wrapper .graphics-item")
        .forEach(graphic =>{
            try {
                new Smart404_chart(graphic);
            }catch(e){
                if(typeof e == "string"){
                    console.error(e)
                }
            }
        })
}
//Initializing Tables;
function initTables(){
    document.querySelectorAll("table.panel-item").forEach(table=>{
        if(table.id ){
            let id = "#" + table.id;
            try{
                new Smart404_datatables(table,new DataTable(id, {"order" : []}));
            }catch(e){
                console.log(e.message)
            }
        }
    })
}
//Initializing custom redirects forms popup
function initPopup(){
    let popup = document.querySelector(".popup-body")
    if(popup){
        let input = popup.querySelector("input#url");
        let  redirect = popup.querySelector("input#redirect");
        let error = popup.querySelector(".error-message");
        document.querySelectorAll(".pop-open").forEach(btn=>{
            btn.addEventListener("click", ()=>{
                error.innerHTML = "";
                !popup.classList.contains("active") ? (popup.classList.add("active")) : null;
                if(btn.hasAttribute("data-404")){
                    input.closest("label").classList.add("disabled");
                    input.value = btn.getAttribute("data-404");
                }else{
                    input.closest("label").classList.remove("disabled");
                    input.value = "";
                }
                if(btn.hasAttribute("data-redirect")){
                    redirect.value = btn.getAttribute("data-redirect");
                }else{
                    redirect.value = "";
                }
            })
        })
        popup.addEventListener("click", (e)=>{
            if(e.target === popup){
                popup.classList.remove("active")
            }
        })
    }
}


//setting switchers
function switcher(){
    document.querySelectorAll(".switcher-wrapper .switcher")
        .forEach(switcher=>{
            switcher.addEventListener("click", ()=>{
                if(typeof switcher.callback === "function"){
                    switcher.callback();
                }
                switcher.classList.contains("active") ? switcher.classList.remove("active") : switcher.classList.add("active");
            })
        })
}

//Initializing post types
function initPostTypes(){
    let table = document.querySelector("table#post_type")
    if(table){
        new Smart404_postTypes(table);
    }
}
//initializing custom redirect remove button
function initRemoveBtn(){
    const xml = new XMLHttpRequest();

    document.querySelectorAll(".btn.remove-btn").forEach(btn=>{
        btn.addEventListener("click", ()=>{
            if(confirm("Do you want to remove this custom redirect?")){
                let row = btn.closest("tr");
                btn.classList.add("preloading");
                const data = new FormData();
                data.append("action", "sm404_remove_custom_redirect_link");
                data.append("url", row.querySelector("td").innerHTML);
                xml.open("POST", ajaxurl);
                xml.send(data);
                xml.onreadystatechange = ()=>{
                    if(xml.readyState === 4){
                        if(xml.status === 200){
                            if(xml.responseText == ""){
                                btn.classList.remove("preloading");
                                row.remove();
                            }else{
                                //setting remove errors
                            }
                        }
                    }
                }
            }
        })
    });
}
//exporting all functions
export function doAllFunctions(){
    initCharts();
    initPopup();
    initTables();
    switcher();
    initPostTypes();
    initRemoveBtn();
    new SmartUrl();
    if(document.querySelector(".sm404-settings-page")){
        new Sm404settings();
    }
}
