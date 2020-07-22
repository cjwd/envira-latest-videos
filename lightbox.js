document.addEventListener('click', lightboxClick);

function lightboxClick(e) {
  var el = e.target,
    elID = el.getAttribute('id'),
    lightboxIframe = document.getElementById('lightbox-iframe'),
    lightbox = document.getElementById('lightbox-overlay'),
    body = document.body;

  if (el.hasAttribute('data-lightbox')) {
    e.preventDefault();

    lightboxIframe.setAttribute('src', el.getAttribute('data-lightbox'));
    lightbox.classList.add('is-visible');
    body.classList.add('no-scroll');
  }

  if (elID == 'lightbox-close' || elID == 'lightbox-overlay') {
    lightbox.classList.remove('is-visible');
    body.classList.remove('no-scroll');
  }
}
