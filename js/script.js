const toggle = document.querySelector('.toggle');
const menu = document.querySelector('.menu');

function toggleMenu() {
    if (menu.classList.contains('active')) {
        menu.classList.remove('active');

        toggle.querySelector('a').innerHTML = '<i class="fa-solid fa-bars"></i>';
        toggle.classList.add('transformed')
    } 
    else {
        menu.classList.add('active');

        toggle.querySelector('a').innerHTML = '<i class="fa-solid fa-xmark"></i>';
        toggle.classList.add('transformed')
    }
}
toggle.addEventListener('click', toggleMenu, false);

