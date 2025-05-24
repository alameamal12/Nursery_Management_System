let slideIndex = 0;
showSlides();

// Next/previous controls
function changeSlide(n) {
    showSlides(slideIndex += n);
}

// Thumbnail image controls
function currentSlide(n) {
    showSlides(slideIndex = n);
}

function showSlides() {
    let i;
    let slides = document.getElementsByClassName("slide");
    
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";  
    }
    slideIndex++;
    
    // Loop back to the first slide
    if (slideIndex > slides.length) {slideIndex = 1}
    
    slides[slideIndex-1].style.display = "block";
    
    // Change image every 3.5 seconds
    setTimeout(showSlides, 3500);
}

// Call showSlides to start the slideshow
showSlides();
