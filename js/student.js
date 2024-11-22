
    function home() {
        document.getElementById("home").style.display = "block";
        document.getElementById("detailsSection").style.display = "none";
        document.getElementById("grades").style.display = "none";
    }
    function details() {
        document.getElementById("detailsSection").style.display = "block";
        document.getElementById("home").style.display = "none";
        document.getElementById("grades").style.display = "none";
    }
    function grades() {
        document.getElementById("grades").style.display = "block";
        document.getElementById("detailsSection").style.display = "none";
        document.getElementById("home").style.display = "none";
    }