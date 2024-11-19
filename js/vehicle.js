
var carCards = document.querySelectorAll(".car-card");
carCards.forEach(function(carCard) {

    var infoIcon = carCard.querySelector(".fa-solid.fa-circle-info");
    var infoPopup = carCard.querySelector(".info-popup");
    
    infoIcon.addEventListener("mouseover", function(){
        infoPopup.style.display = "block";
    });

    infoIcon.addEventListener("mouseout", function(){
        infoPopup.style.display = "none";
    });

});