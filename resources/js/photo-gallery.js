class PhotoGallery {
    constructor(containerId) {
        this.container = document.getElementById(containerId);
        this.currentIndex = 0;
        this.photos = [];
        this.init();
    }

    init() {
        if (!this.container) return;

        this.mainImage = this.container.querySelector('.main-image');
        this.thumbnails = this.container.querySelectorAll('.thumbnail');
        this.lightbox = document.querySelector('.lightbox');
        this.lightboxImage = document.querySelector('.lightbox-image');

        this.photos = Array.from(this.thumbnails).map(thumb => thumb.src);

        // Add event listeners
        this.thumbnails.forEach((thumb, index) => {
            thumb.addEventListener('click', () => this.setImage(index));
        });

        if (this.mainImage) {
            this.mainImage.addEventListener('click', () => this.openLightbox());
        }

        if (this.lightbox) {
            this.lightbox.addEventListener('click', (e) => {
                if (e.target === this.lightbox) this.closeLightbox();
            });
        }

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (this.lightbox && this.lightbox.classList.contains('active')) {
                if (e.key === 'ArrowLeft') this.prevImage();
                if (e.key === 'ArrowRight') this.nextImage();
                if (e.key === 'Escape') this.closeLightbox();
            }
        });
    }

    setImage(index) {
        this.currentIndex = index;
        if (this.mainImage) {
            this.mainImage.src = this.photos[index];
        }
        this.thumbnails.forEach((thumb, i) => {
            if (i === index) {
                thumb.classList.add('active');
            } else {
                thumb.classList.remove('active');
            }
        });
        this.updateCounter();
    }

    openLightbox() {
        if (!this.lightbox) return;
        this.lightbox.classList.add('active');
        this.updateLightboxImage();
    }

    closeLightbox() {
        if (!this.lightbox) return;
        this.lightbox.classList.remove('active');
    }

    updateLightboxImage() {
        if (this.lightboxImage) {
            this.lightboxImage.src = this.photos[this.currentIndex];
        }
    }

    nextImage() {
        this.currentIndex = (this.currentIndex + 1) % this.photos.length;
        this.setImage(this.currentIndex);
        this.updateLightboxImage();
    }

    prevImage() {
        this.currentIndex = (this.currentIndex - 1 + this.photos.length) % this.photos.length;
        this.setImage(this.currentIndex);
        this.updateLightboxImage();
    }

    updateCounter() {
        const counter = this.container.querySelector('.image-counter');
        if (counter) {
            counter.textContent = `${this.currentIndex + 1} / ${this.photos.length}`;
        }
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    new PhotoGallery('property-gallery');
});