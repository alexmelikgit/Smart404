class Smart404_datatables{constructor(t,e){this.table=t,this.datatable=e,this.wrapper=t.closest(".graphics-wrapper"),this.id=t.id,this.init()}init(){this.lengthToggler=this.wrapper.querySelector(".dataTables_length select"),this.length=localStorage.getItem(this.id),this.setLength()}setLength(){this.lengthToggler.addEventListener("change",()=>{localStorage.setItem(this.id,this.lengthToggler.value)}),this.length&&this.datatable.page.len(parseInt(this.length)).draw()}}export{Smart404_datatables};
//# sourceMappingURL=data:application/json;charset=utf8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoic21hcnQ0MDRfZGF0YXRhYmxlcy5qcyIsInNvdXJjZXMiOlsic21hcnQ0MDRfZGF0YXRhYmxlcy5qcyJdLCJzb3VyY2VzQ29udGVudCI6WyJleHBvcnQgY2xhc3MgU21hcnQ0MDRfZGF0YXRhYmxlc3tcclxuICAgIGNvbnN0cnVjdG9yKHRhYmxlLCBkYXRhdGFibGUpIHtcclxuICAgICAgICB0aGlzLnRhYmxlID0gdGFibGU7XHJcbiAgICAgICAgdGhpcy5kYXRhdGFibGUgPSBkYXRhdGFibGU7XHJcbiAgICAgICAgdGhpcy53cmFwcGVyID0gdGFibGUuY2xvc2VzdChcIi5ncmFwaGljcy13cmFwcGVyXCIpO1xyXG4gICAgICAgIHRoaXMuaWQgPSB0YWJsZS5pZDtcclxuICAgICAgICB0aGlzLmluaXQoKTtcclxuXHJcbiAgICB9XHJcbiAgICBpbml0KCl7XHJcbiAgICAgICAgdGhpcy5sZW5ndGhUb2dnbGVyID0gdGhpcy53cmFwcGVyLnF1ZXJ5U2VsZWN0b3IoXCIuZGF0YVRhYmxlc19sZW5ndGggc2VsZWN0XCIpO1xyXG4gICAgICAgIHRoaXMubGVuZ3RoID0gbG9jYWxTdG9yYWdlLmdldEl0ZW0odGhpcy5pZCk7XHJcbiAgICAgICAgdGhpcy5zZXRMZW5ndGgoKTtcclxuICAgIH1cclxuICAgIHNldExlbmd0aCgpe1xyXG4gICAgICAgIHRoaXMubGVuZ3RoVG9nZ2xlci5hZGRFdmVudExpc3RlbmVyKFwiY2hhbmdlXCIsICgpPT57XHJcbiAgICAgICAgICAgIGxvY2FsU3RvcmFnZS5zZXRJdGVtKHRoaXMuaWQsIHRoaXMubGVuZ3RoVG9nZ2xlci52YWx1ZSk7XHJcbiAgICAgICAgfSlcclxuICAgICAgICBpZih0aGlzLmxlbmd0aCl7XHJcbiAgICAgICAgICAgIHRoaXMuZGF0YXRhYmxlLnBhZ2UubGVuKHBhcnNlSW50KHRoaXMubGVuZ3RoKSkuZHJhdygpO1xyXG4gICAgICAgIH1cclxuICAgIH1cclxufSJdLCJuYW1lcyI6WyJTbWFydDQwNF9kYXRhdGFibGVzIiwiY29uc3RydWN0b3IiLCJ0YWJsZSIsImRhdGF0YWJsZSIsInRoaXMiLCJ3cmFwcGVyIiwiY2xvc2VzdCIsImlkIiwiaW5pdCIsImxlbmd0aFRvZ2dsZXIiLCJxdWVyeVNlbGVjdG9yIiwibGVuZ3RoIiwibG9jYWxTdG9yYWdlIiwiZ2V0SXRlbSIsInNldExlbmd0aCIsImFkZEV2ZW50TGlzdGVuZXIiLCJzZXRJdGVtIiwidmFsdWUiLCJwYWdlIiwibGVuIiwicGFyc2VJbnQiLCJkcmF3Il0sIm1hcHBpbmdzIjoiTUFBYUEsb0JBQ1RDLFlBQVlDLEVBQU9DLEdBQ2ZDLEtBQUtGLE1BQVFBLEVBQ2JFLEtBQUtELFVBQVlBLEVBQ2pCQyxLQUFLQyxRQUFVSCxFQUFNSSxRQUFRLG1CQUFtQixFQUNoREYsS0FBS0csR0FBS0wsRUFBTUssR0FDaEJILEtBQUtJLEtBQUssQ0FFZCxDQUNBQSxPQUNJSixLQUFLSyxjQUFnQkwsS0FBS0MsUUFBUUssY0FBYywyQkFBMkIsRUFDM0VOLEtBQUtPLE9BQVNDLGFBQWFDLFFBQVFULEtBQUtHLEVBQUUsRUFDMUNILEtBQUtVLFVBQVUsQ0FDbkIsQ0FDQUEsWUFDSVYsS0FBS0ssY0FBY00saUJBQWlCLFNBQVUsS0FDMUNILGFBQWFJLFFBQVFaLEtBQUtHLEdBQUlILEtBQUtLLGNBQWNRLEtBQUssQ0FDMUQsQ0FBQyxFQUNFYixLQUFLTyxRQUNKUCxLQUFLRCxVQUFVZSxLQUFLQyxJQUFJQyxTQUFTaEIsS0FBS08sTUFBTSxDQUFDLEVBQUVVLEtBQUssQ0FFNUQsQ0FDSixRQXRCYXJCLG1CQXNCYiJ9
