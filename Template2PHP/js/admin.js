/* sidebar */
function openNav(){
    document.getElementById("admin_Sidebar").style.width = "250px";
   
    document.getElementById("main-content").style.margineLeft = "250px";
    document.getElementById("main").style.display = "none";
}
function closeNav(){
    document.getElementById("admin_Sidebar").style.width = "0";
    document.getElementById("main-content").style.marginLeft = "0";
    document.getElementById("main-content").style.display = "block";
}