import '../styles/gallery.css';

/*
 * Set the width of the side navigation to 14rem (category button's width)
 * on click or toggle it back to 0
 */
function toggleSideNav() {
    const sidenav = document.getElementById('mySidenav');
    if (sidenav.style.width === '14rem') {
        sidenav.style.width = '0';
        sidenav.style.marginLeft = '-2px';
    } else {
        sidenav.style.width = '14rem';
        sidenav.style.marginLeft = '0';
    }
}

/* Event listener to trigger the toggleSideNav function */

document.getElementById('categories-pop').addEventListener('click', toggleSideNav, false);
document.getElementById('closebtn').addEventListener('click', toggleSideNav, false);

/*
 * Article overlay on click maker
 */
/**
 * @property {HTMLElement} element
 * @property {string} url Currently displayed image
 */

class Lightbox {
    static init() {
        document.body.addEventListener('click', (e) => {
            if (e.target && e.target.matches('.gallery-img')) {
                e.preventDefault();
                const currentSrc = e.target.src;
                const currentAlt = e.target.alt;
                const imageId = e.target.id;
                const request = new Request(`/article/${imageId}`, { method: 'GET' });
                const articleStuff = [];
                fetch(request)
                    .then((response) => response.json())
                    .then((articleStuffObj) => {
                        articleStuff.push(articleStuffObj.data);
                        const lightbox = new Lightbox(articleStuff[0], currentSrc, currentAlt);
                    });
            }
        }, false);
    }

    /**
     * @param {array} articleStuff content of the article associated to the clicked image
     * @param {string} currentSrc Image URL
     * @param {string} currentAlt Image alternative text
     */
    constructor(articleStuff, currentSrc, currentAlt) {
        this.element = this.buildDOM(articleStuff, currentSrc, currentAlt);
        document.body.appendChild(this.element);
    }

    /**
     * Close the lightbox
     * @param {MouseEvent} e
     */
    close(e) {
        e.preventDefault();
        const lightboxes = document.getElementsByClassName('lightbox');
        lightboxes.forEach((lightbox) => {
            lightbox.classList.add('fadeOut');
            window.setTimeout(() => {
                lightbox.remove();
            }, 500);
        });
    }

    /**
     * @param {array} articleStuff content of the article associated to the clicked image
     * @param {string} currentSrc url of the image
     * @param {string} currentAlt image alternative text
     * @return {HTMLElement}
     */
    buildDOM(articleStuff, currentSrc, currentAlt) {
        let images = '';
        if (articleStuff.images) {
            for (let i = 0; i < articleStuff.images.length; i += 1) {
                if (!currentSrc.includes(articleStuff.images[i].url)) {
                    images += `<img id=${articleStuff.images[i].id} class="gallery-img lightbox_mini" src="${articleStuff.images[i].url}" alt="${articleStuff.images[i].alternativeText}"> `;
                }
            }
        }
        const dom = document.createElement('div');
        dom.classList.add('lightbox');
        dom.innerHTML = `<button class="lightbox__close"></button>
        <div class="lightbox__container">
            <h1 id="article_title">${articleStuff.title.charAt(0).toUpperCase() + articleStuff.title.slice(1)}</h1>
            <div class="lightbox_image-border">
                <div class="lightbox_main-image-container">
                    <img class="lightbox_image" src="${currentSrc}" alt="${currentAlt}">
                </div>
                <div class="lightbox_image-list ">${images} </div>
            </div>
            <div class="lightbox_text">
                ${articleStuff.content}   
            </div>
        </div>`;
        dom.querySelector('.lightbox__close').addEventListener('click', this.close.bind(this));
        return dom;
    }
}
Lightbox.init();
