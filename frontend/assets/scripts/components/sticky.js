export default function stickyNav() {
    const header = document.getElementById('masthead');
    const body = document.body;
    const sticky = header.offsetTop + 0;

    window.addEventListener('scroll', () => {
        if (body.classList.contains('rs-sticky')) {
            if (window.scrollY > sticky) {
                body.classList.add('is-sticky');
            } else {
                body.classList.remove('is-sticky');
            }
        }
    });
}