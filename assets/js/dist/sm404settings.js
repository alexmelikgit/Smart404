class Sm404settings{constructor(){this.autoRedirect=document.querySelector(".auto-redirect-switcher .switcher"),this.init()}init(){this.setAutoRedirect()}setAutoRedirect(){this.autoRedirect.callback=()=>{if(confirm("Do you want change autoredirect status? after changing the status, the page will be reloaded, please save all unchanged datas")){const t=new XMLHttpRequest;t.open("PUT",ajaxurl+"?action=change_auto_redirect"),t.send(JSON.stringify({0:!0})),t.onreadystatechange=()=>{4===t.readyState&&200===t.status&&location.reload()}}}}}export{Sm404settings};
//# sourceMappingURL=data:application/json;charset=utf8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoic200MDRzZXR0aW5ncy5qcyIsInNvdXJjZXMiOlsic200MDRzZXR0aW5ncy5qcyJdLCJzb3VyY2VzQ29udGVudCI6WyJleHBvcnQgY2xhc3MgU200MDRzZXR0aW5nc3tcclxuICAgIGNvbnN0cnVjdG9yKCkge1xyXG4gICAgICAgIHRoaXMuYXV0b1JlZGlyZWN0ID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcihcIi5hdXRvLXJlZGlyZWN0LXN3aXRjaGVyIC5zd2l0Y2hlclwiKTtcclxuICAgICAgICB0aGlzLmluaXQoKTtcclxuICAgIH1cclxuXHJcbiAgICBpbml0KCl7XHJcbiAgICAgICAgdGhpcy5zZXRBdXRvUmVkaXJlY3QoKTtcclxuICAgIH1cclxuXHJcbiAgICBzZXRBdXRvUmVkaXJlY3QoKXtcclxuICAgICAgICB0aGlzLmF1dG9SZWRpcmVjdC5jYWxsYmFjayA9ICgpPT57XHJcbiAgICAgICAgICAgIGlmKGNvbmZpcm0oXCJEbyB5b3Ugd2FudCBjaGFuZ2UgYXV0b3JlZGlyZWN0IHN0YXR1cz8gYWZ0ZXIgY2hhbmdpbmcgdGhlIHN0YXR1cywgdGhlIHBhZ2Ugd2lsbCBiZSByZWxvYWRlZCwgcGxlYXNlIHNhdmUgYWxsIHVuY2hhbmdlZCBkYXRhc1wiKSl7XHJcbiAgICAgICAgICAgICAgICBjb25zdCB4bWwgPSBuZXcgWE1MSHR0cFJlcXVlc3QoKTtcclxuICAgICAgICAgICAgICAgIHhtbC5vcGVuKFwiUFVUXCIsIGFqYXh1cmwgKyBcIj9hY3Rpb249Y2hhbmdlX2F1dG9fcmVkaXJlY3RcIik7XHJcbiAgICAgICAgICAgICAgICB4bWwuc2VuZChKU09OLnN0cmluZ2lmeSh7MCA6IHRydWV9KSk7XHJcbiAgICAgICAgICAgICAgICB4bWwub25yZWFkeXN0YXRlY2hhbmdlID0gKCk9PntcclxuICAgICAgICAgICAgICAgICAgICBpZih4bWwucmVhZHlTdGF0ZSA9PT0gNCAmJiB4bWwuc3RhdHVzID09PSAyMDApe1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBsb2NhdGlvbi5yZWxvYWQoKVxyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH1cclxuICAgIH1cclxufSJdLCJuYW1lcyI6WyJTbTQwNHNldHRpbmdzIiwiY29uc3RydWN0b3IiLCJ0aGlzIiwiYXV0b1JlZGlyZWN0IiwiZG9jdW1lbnQiLCJxdWVyeVNlbGVjdG9yIiwiaW5pdCIsInNldEF1dG9SZWRpcmVjdCIsImNhbGxiYWNrIiwiY29uZmlybSIsInhtbCIsIlhNTEh0dHBSZXF1ZXN0Iiwib3BlbiIsImFqYXh1cmwiLCJzZW5kIiwiSlNPTiIsInN0cmluZ2lmeSIsIjAiLCJvbnJlYWR5c3RhdGVjaGFuZ2UiLCJyZWFkeVN0YXRlIiwic3RhdHVzIiwibG9jYXRpb24iLCJyZWxvYWQiXSwibWFwcGluZ3MiOiJNQUFhQSxjQUNUQyxjQUNJQyxLQUFLQyxhQUFlQyxTQUFTQyxjQUFjLG1DQUFtQyxFQUM5RUgsS0FBS0ksS0FBSyxDQUNkLENBRUFBLE9BQ0lKLEtBQUtLLGdCQUFnQixDQUN6QixDQUVBQSxrQkFDSUwsS0FBS0MsYUFBYUssU0FBVyxLQUN6QixHQUFHQyxRQUFRLCtIQUErSCxFQUFFLENBQ3hJLE1BQU1DLEVBQU0sSUFBSUMsZUFDaEJELEVBQUlFLEtBQUssTUFBT0MsUUFBVSw4QkFBOEIsRUFDeERILEVBQUlJLEtBQUtDLEtBQUtDLFVBQVUsQ0FBQ0MsRUFBSSxDQUFBLENBQUksQ0FBQyxDQUFDLEVBQ25DUCxFQUFJUSxtQkFBcUIsS0FDQyxJQUFuQlIsRUFBSVMsWUFBbUMsTUFBZlQsRUFBSVUsUUFDM0JDLFNBQVNDLE9BQU8sQ0FFeEIsQ0FDSixDQUNKLENBQ0osQ0FDSixRQXhCYXRCLGFBd0JiIn0=
