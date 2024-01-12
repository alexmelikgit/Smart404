import {doAllFunctions} from "./assets.js?v=1.0.1";
document.onreadystatechange = ()=>{
    if(document.readyState === "complete"){
        doAllFunctions();
    }
}