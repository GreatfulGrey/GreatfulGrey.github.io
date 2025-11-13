// Function to initialize all carousels on the page
function initializeCarousels() {
    // Select all carousel containers
    const carousels = document.querySelectorAll('.carousel');

    carousels.forEach(carousel => {
        const track = carousel.querySelector('.carousel-track');
        // Get all images (slides) within the track
        const slides = Array.from(track.children);
        
        // Find the specific buttons for this carousel
        const prevButton = carousel.querySelector('.prev');
        const nextButton = carousel.querySelector('.next');
        
        let currentSlideIndex = 0; // Start at the first image (index 0)
        
        // Get the width of a single slide to calculate the necessary translation
        // This assumes all slides have the same width as the viewport
        const slideWidth = slides[0].getBoundingClientRect().width;

        // Function to move the carousel track
        const moveToSlide = (track, targetIndex) => {
            // Calculate how far to move the track (negative index * width)
            track.style.transform = 'translateX(-' + (targetIndex * slideWidth) + 'px)';
            currentSlideIndex = targetIndex;
        }

        // --- Event Listeners for Buttons ---

        // Move to the previous slide
        prevButton.addEventListener('click', e => {
            let targetIndex = currentSlideIndex - 1;
            
            // Loop back to the last slide if at the beginning
            if (targetIndex < 0) {
                targetIndex = slides.length - 1;
            }
            
            moveToSlide(track, targetIndex);
        });

        // Move to the next slide
        nextButton.addEventListener('click', e => {
            let targetIndex = currentSlideIndex + 1;
            
            // Loop back to the first slide if at the end
            if (targetIndex >= slides.length) {
                targetIndex = 0;
            }
            
            moveToSlide(track, targetIndex);
        });
        
        // IMPORTANT: Handle resizing. If the window size changes, the slide width changes.
        window.addEventListener('resize', () => {
            // Recalculate and re-position the current slide
            const newSlideWidth = slides[0].getBoundingClientRect().width;
            track.style.transform = 'translateX(-' + (currentSlideIndex * newSlideWidth) + 'px)';
        });
    });
}

// Run the initialization function once the entire document is loaded
document.addEventListener('DOMContentLoaded', initializeCarousels);