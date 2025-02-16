class Carousel {
    constructor(body, slide) {
        this.body = body;
        this.slides = slide;
        this.index = 0;
        this.length = this.slides.length;
    }

    next() {
        this.index = (this.index + 1) % this.length;
        const slide = this.slides[this.index];
        this.body.scrollLeft+=slide.offsetWidth;
    }

    prev() {
        this.index = (this.index - 1 + this.length) % this.length;
        const slide = this.slides[this.index];
        this.body.scrollLeft-=slide.offsetWidth;
    }

}

window.onload = function() {

    for(const carousel of document.querySelectorAll('.carousel')) {
        const csl = new Carousel(carousel.querySelector('.carousel-wrapper'), carousel.querySelectorAll('.carousel-slide'));
        carousel.querySelector('.carousel-next').addEventListener('click', () => csl.next());
        carousel.querySelector('.carousel-prev').addEventListener('click', () => csl.prev());
    }
    
}
