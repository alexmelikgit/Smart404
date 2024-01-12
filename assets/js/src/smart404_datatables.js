export class Smart404_datatables{
    constructor(table, datatable) {
        this.table = table;
        this.datatable = datatable;
        this.wrapper = table.closest(".graphics-wrapper");
        this.id = table.id;
        this.init();

    }
    init(){
        this.lengthToggler = this.wrapper.querySelector(".dataTables_length select");
        this.length = localStorage.getItem(this.id);
        this.setLength();
    }
    setLength(){
        this.lengthToggler.addEventListener("change", ()=>{
            localStorage.setItem(this.id, this.lengthToggler.value);
        })
        if(this.length){
            this.datatable.page.len(parseInt(this.length)).draw();
        }
    }
}