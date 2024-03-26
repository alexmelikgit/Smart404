export class Smart404_chart{

    constructor(element) {
        this.canvas = element
        if(!(this.canvas instanceof HTMLCanvasElement)){
            throw "Canvas not exists"
        }
        this.wrapper = this.canvas.closest(".graphics-wrapper");
        this.configs = {};
        this.redirectType = this.canvas.closest("[data-redirect-type]");
        this.redirectType = this.redirectType ? this.redirectType.getAttribute('data-redirect-type') : null;
        this.init();
    }
    init(){
        this.setButtons();
        this.setFilter();
        this.setDefConfigs();
        this.chart = new Chart(this.canvas,this.configs);
        this.#setDefData();
    }
    //Initializing Filter Functionality
    setFilter(){
        this.filterInput = this.wrapper.querySelector("input[type=month].month-filter");
        this.filterReset = this.wrapper.querySelector("button.filter-reset.btn");
        if(this.filterInput && this.filterReset){
            this.#setFilter();
            this.filterReset.addEventListener("click", ()=>{
                this.#setDefData();
            })
        }
    }
    //setting filter buttons
    setButtons(){
        this.setWeekButton();
        this.setNextButton()
        this.setBackButton();
    }

    //setting functionality for chart next button
    setNextButton(){
        this.nextButton = this.wrapper.querySelector(".btn.next");
        let toNextMont = (date)=>{
            let year = date.getFullYear();
            let month = date.getMonth();
            if(date.getMonth() === 11){
                year++;
                month = 0;
            }else{
                month++;
            }
            this.#setMonth(year,month+1);
        }
        if(this.nextButton){
            this.nextButton.addEventListener("click", ()=>{
                let endDate = this.wrapper.getAttribute("data-end") ?? null;
                let date = endDate ? Smart404_chart.getDateFromString(endDate) : new Date() ;
                if(this.wrapper.hasAttribute("data-filter")){
                    if(this.wrapper.getAttribute("data-filter") === "week"){
                        let startDate = new Date(date.getTime() + (7*24*60*60*1000));
                        this.wrapper.setAttribute("data-start", Smart404_chart.getStringFromDate(startDate))
                        this.#setWeek();
                    }else{
                        toNextMont(date);
                    }
                }else{
                    toNextMont(date);
                }
            })

        }

    }
    //setting functionality for chart back button
    setBackButton(){
        this.backButton = this.wrapper.querySelector(".btn.back");
        const toPrevMonth = (date)=>{
            let year = date.getFullYear();
            let month = date.getMonth();
            if(date.getMonth() === 0){
                year--;
                month = 11;
            }else{
                month--;
            }
            this.#setMonth(year,month+1);
        }
        if(this.backButton){
            this.backButton.addEventListener("click", ()=>{
                let startDate = this.wrapper.getAttribute("data-start") ?? null;
                let date = startDate ? Smart404_chart.getDateFromString(startDate) : new Date();
                if(this.wrapper.hasAttribute("data-filter")){
                    if(this.wrapper.getAttribute("data-filter") === "week"){
                        let startDate = new Date(date.getTime() - (24*60*60*1000));
                        this.wrapper.setAttribute("data-start", Smart404_chart.getStringFromDate(startDate));
                        this.#setWeek();
                    }else{
                        toPrevMonth(date);
                    }
                }else{
                    toPrevMonth(date);
                }
            })
        }
    }
    //setting week button
    setWeekButton(){
        this.weekButton = this.wrapper.querySelector(".switcher.week-btn");
        if(this.weekButton){

            this.weekButton.callback = ()=>{
                if(this.wrapper.hasAttribute("data-filter")){
                    let filter = this.wrapper.getAttribute("data-filter");
                    if(filter === "month"){
                        localStorage.setItem("chart-by-week", true);

                        this.wrapper.setAttribute("data-filter", "week");
                        this.#setWeek();
                    }else{
                        localStorage.setItem("chart-by-week", false);
                        this.wrapper.setAttribute("data-filter", "month");
                        let date = this.wrapper.hasAttribute("data-end") ? Smart404_chart.getDateFromString(this.wrapper.getAttribute("data-end")) : new Date();
                        this.#setMonth(date.getFullYear(), date.getMonth()+1, false);
                    }
                }
            }
        }

    }
    //setting default data
    #setDefData(){
        const date = new Date();
        this.wrapper.removeAttribute("data-start")
        this.wrapper.removeAttribute("data-end")
        if(localStorage.getItem("chart-by-week") == "true"){
            this.#setWeek();
            this.weekButton.classList.add("active")
        }else{
            this.#setMonth(date.getFullYear(), date.getMonth()+1);

        }
    }
    //setting filter functionality
    #setFilter(){
        this.filterInput.addEventListener("change", ()=>{
            this.weekButton.classList.remove("active")
            const year = this.filterInput.value.substring(0,4);
            const month = this.filterInput.value.substring(5,7);
            this.wrapper.setAttribute('data-filter', "month")
            this.#setMonth(year,month);
        })
    }

    //setting filter by month
    #setMonth(year, month, setAttriubte = true){
        const startDate = `${year}-${month}-01`;
        const endDate = `${year}-${month}-${Smart404_chart.getLastDayofMonth(year,month)}`;
        this.#setData({start: startDate, end: endDate}, setAttriubte)
        this.wrapper.setAttribute("data-filter", "month");
    }
    //week setter for chart
    #setWeek(){
        let start = this.wrapper.hasAttribute("data-start") ? this.wrapper.getAttribute("data-start") : Smart404_chart.getStringFromDate(new Date());
        let date = new Date();
        if(start){
            let dateArr = start.split("-");
            this.#setData(this.#getWeek(new Date(dateArr[0], parseInt(dateArr[1])-1, dateArr[2])));
        }else{
            let dateArr = [date.getFullYear(), date.getMonth(), date.getDate()];
            this.#getWeek(new Date(dateArr[0], parseInt(dateArr[1])-1, dateArr[2]));
        }
        this.wrapper.setAttribute("data-filter", "week");
    }
    //getting weeks between two date
    #getWeek(date = null){
        date = date ?? new Date();
        let startDate = null;
        let endDate = null;
        if(date.getDay()){
            startDate = new Date(date.getTime() - (date.getDay()  * 24 * 60 * 60 * 1000));
            endDate = new Date(date.getTime() + ((6 - date.getDay()) * 24 * 60 * 60 * 1000));
        }else{
            startDate = date;
            endDate = new Date(date.getTime() + (6 * 24 * 60 * 60 * 1000)) ;

        }

        return {start: Smart404_chart.getStringFromDate(startDate), end: Smart404_chart.getStringFromDate(endDate)};
    }
    //getting string of date (Y-m-d) from date object
    static getStringFromDate(date){
        return `${date.getFullYear()}-${date.getMonth()+1}-${date.getDate()}`;
    }
    //getting date object from string (Y-m-d)
    static getDateFromString(string){
        let dateArr = string.split("-");
        return new Date(dateArr[0],parseInt(dateArr[1])-1,dateArr[2]);
    }
    //Setting Chart Default Configs
    setDefConfigs(){
        this.configs = {
            type: "line",
            data :  {
                labels: [],
                datasets: [],
            },
            options : {
                scales: {
                    x: {
                        type: 'time',
                        time : {
                            unit: "day"
                        }
                    },
                    y: {
                        beginAtZero : true,
                    },
                }
            },
        }
    }
    static getLastDayofMonth(y,m){
        return new Date(y,m,0).getDate();
    }
    async #getData(date){
        const data = new FormData();
        data.append("action", "smart404_getdata");
        data.append("date", JSON.stringify(date));
        data.append("redirect_type", this.redirectType);
        const response = await fetch(ajaxurl, {
            body: data,
            method : "POST"
        });
        return await response.json();
    }
    #setData(dateDiapason, setAttribute = true){
        this.wrapper.classList.add("loading");
        this.#getData(dateDiapason).then(data=>{
            let dataset = [
                {
                    label : new Date(dateDiapason.end).toLocaleString("default", {month: "long"}),
                    borderColor: "rgb(0,0,255)",
                    tension : .1,
                    data : data
                }
            ]
            this.chart.config.options.scales.x.min = dateDiapason.start;
            this.chart.config.options.scales.x.max = dateDiapason.end;
            this.chart.data.datasets = dataset;
            this.chart.update();
            this.wrapper.classList.remove("loading");
        })
        if(setAttribute){
            this.wrapper.setAttribute("data-start", dateDiapason.start);
            this.wrapper.setAttribute("data-end", dateDiapason.end);
        }
        let date = Smart404_chart.getDateFromString(dateDiapason.start);
        let month = date.getMonth() < 9 ? "0" + (date.getMonth() + 1) : date.getMonth() + 1;
        this.filterInput.value = date.getFullYear() + "-" + month;

    }
}

