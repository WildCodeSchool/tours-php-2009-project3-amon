import '../styles/home.css';

const slideSource = document.getElementById('header');

function show() {
    slideSource.classList.toggle('fade');
}

function hide() {
    slideSource.classList.toggle('fade');
}

// user hits button down and the navbar is hidden, we show it.
const btnDown = document.getElementById('button-down');

btnDown.addEventListener('click', (e) => {
    if ((e.target.id === 'button-down' || (e.target.classList.contains('arrow') && e.target.parentElement.id === 'button-down')) && !slideSource.classList.contains('fade')) {
        show();
    }
}, false);

// user hits button up and the navbar is visible, we hide it.
const btnUp = document.getElementById('button-up');

btnUp.addEventListener('click', (e) => {
    if ((e.target.id === 'button-up' || (e.target.classList.contains('arrow') && e.target.parentElement.id === 'button-up')) && slideSource.classList.contains('fade')) {
        hide();
    }
}, false);

// handle navbar visibility status according to user scroll position
document.addEventListener('scroll', () => {
    if (window.scrollY < document.body.offsetHeight * 0.35 && slideSource.classList.contains('fade')) {
        hide();
    } else if (window.scrollY > document.body.offsetHeight * 0.35 && !slideSource.classList.contains('fade')) {
        show();
    }
}, false);
