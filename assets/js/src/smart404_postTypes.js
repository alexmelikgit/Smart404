export class Smart404_postTypes{
    constructor(table) {
        this.table = table;
        this.updatesArr = []
        this.types = this.table.querySelectorAll("tbody tr");
        this.updateBtn = this.table.querySelector(".update-btn");
        this.init();

    }
    init(){
        this.setSwitchers();
        this.switcherAjax();
    }
    setSwitchers(){
        this.types.forEach(type=>{
            type.switcher = type.querySelector(".switcher-wrapper .switcher");
            type.post_type = type.querySelector("td").innerHTML;
            if(type.switcher){
                type.switcher.callback = ()=>{
                    if(this.updatesArr.includes(type.post_type)){
                        let index = this.updatesArr.indexOf(type.post_type);
                        this.updatesArr.splice(index, 1);
                    }else{
                        this.updatesArr.push(type.post_type);
                    }
                    if(this.updatesArr.length){
                        this.updateBtn.classList.add("active");
                    }else{
                        this.updateBtn.classList.remove("active");
                    }
                }
            }
        })
    }

    //get switcher values
    getSwitcherValues(){
        let results = [];
        this.table.querySelectorAll(".switcher.active").forEach(active=>{
            let type = active.closest("tr");
            results.push(type.post_type)
        });
        return results;
    }
    //setting ajax request for update
    switcherAjax(){
        const xml = new XMLHttpRequest();
        const data = new FormData();
        data.append("action", "sm404_post_types");
        data.append("data", []);
        this.updateBtn.addEventListener("click",()=>{
            data.set("data", JSON.stringify(this.getSwitcherValues()))
            xml.open("POST", ajaxurl + "?action=sm404_post_types");
            xml.send(data);
        })
        xml.onreadystatechange = ()=>{
            if(xml.readyState === 4){
                if(xml.status === 200){
                    window.location.reload();
                }
            }
        }
    }
}
