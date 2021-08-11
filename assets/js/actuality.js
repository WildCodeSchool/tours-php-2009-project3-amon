/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../styles/actuality.css';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';

/**
 * @property {HTMLElement} element
 * @property {string[]} images Path of images in lightbox
 * @property {string} url Currently displayed image
 */
class Lightbox {
    static init() {
        const links = Array.from(document.querySelectorAll('a[href$=".png"], a[href$=".jpeg"], a[href$=".gif"], a[href$=".jpg"], a[href$=".svg"]'));
        const images = links.map((link) => link.getAttribute('href'));
        links.forEach((link) => link.addEventListener('click', (e) => {
            e.preventDefault(); const lightbox = new Lightbox(e.currentTarget.getAttribute('href'), images);
        }));
    }

    /**
     * @param {string} url Image URL
     * @param {string[]} images Path of images in lightbox
     */
    constructor(url, images) {
        this.element = this.buildDOM(url);
        this.images = images;
        document.body.appendChild(this.element);
    }

    /**
     * @param {string} url Image URL
     */
    loadImage(url) {
        this.url = null;
        const image = new Image();
        const container = this.element.querySelector('.lightbox__container');
        const loader = document.createElement('div');
        loader.classList.add('lightbox__loader');
        container.innerHTML = '';
        container.appendChild(loader);
        image.onload = () => {
            container.removeChild(loader);
            container.appendChild(image);
            this.url = url;
        };
        image.src = url;
    }

    /**
     * Close the lightbox
     * @param {MouseEvent} e
     */
    close(e) {
        e.preventDefault();
        this.element.classList.add('fadeOut');
        window.setTimeout(() => {
            this.element.remove();
        }, 500);
    }

    /**
     * @param {string} url URL de l'image
     * @return {HTMLElement}
     */
    buildDOM(url) {
        const dom = document.createElement('div');
        dom.classList.add('lightbox');
        dom.innerHTML = `<button class="lightbox__close"></button>
        <div class="lightbox__container">
            <img class="lightbox_container_img" src="${url}" alt"">
        </div>`;
        dom.querySelector('.lightbox__close').addEventListener('click', this.close.bind(this));
        return dom;
    }
}

Lightbox.init();
