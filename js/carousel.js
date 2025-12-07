// Inisialisasi carousel
var myCarousel = document.getElementById('carouselExample');
var carousel = new bootstrap.Carousel(myCarousel);

// Set interval untuk otomatis bergeser setiap 3 detik
setInterval(function(){
  carousel.next();
}, 4000);
