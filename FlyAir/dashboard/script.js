window.onload = function() {

    var destinations = document.querySelectorAll('.destination');

    destinations.forEach(function(destination) {
        destination.onclick = function() {
            var navbar = document.getElementById('navbar');
            navbar.scrollIntoView({
                behavior: 'smooth'
            });
        };
    });


   document.getElementById("returnTripCheckbox").onclick = function returntrip() {
        var returndatediv = document.querySelector(".returndate");
        var returncheckbox = document.getElementById("returnTripCheckbox");
        var departuredate = document.querySelector(".departuredate");

        if(returncheckbox.checked) {
            departuredate.style.width = "49.5%";
            returndatediv.style.display = "flex";
            returndatediv.style.width = "49.5%";
        }else{
            departuredate.style.width = "100%";
            returndatediv.style.display = "none";
        }
        
    };
    
};
