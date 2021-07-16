import '../styles/confirmationModal.css';

// toggle modal shown/hidden
function toggleModal(href = '#') {
    const modal = document.getElementById('modalContainer');
    const btn = modal.querySelector('#confirmActionLink');
    btn.href = href;
    if (modal.style.display !== 'none') {
        modal.style.display = 'none';
    } else {
        modal.style.display = 'flex';
    }
}
document.addEventListener('click', (e) => {
    const modal = document.getElementById('modalContainer');
    if (e.target.classList.contains('modal-trigger')) {
        e.preventDefault();
        const { href } = e.target;
        toggleModal(href);
    } else if (modal.style.display === 'flex' && e.target.id !== 'confirmActionLink') {
        toggleModal();
    }
}, true);
