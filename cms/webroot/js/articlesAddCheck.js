
let myselfRadio = document.getElementById("myself");
let otherRadio =  document.getElementById("other");

let addForOthers = document.getElementById("addForOthers");
let otherList = document.getElementById("otherList");
let loggedUserId = document.getElementById("loggedUserId");

//toggle dropdown to invisible and reset to default user
myselfRadio.addEventListener("click", ()=> {
    addForOthers.style.display = "none";
    otherList.value = loggedUserId.value; 
});
//toggle dropdown to visible
otherRadio.addEventListener("click", ()=> addForOthers.style.display = "initial" );
 
