document.addEventListener("DOMContentLoaded", () => {
    const menuIcon = document.getElementById("menu-icon");
    const navbar = document.getElementById("navbar");
    menuIcon.addEventListener("click", () => {
      navbar.classList.toggle("active");
    });
  
  });


const sliderTrack = document.querySelector('#containerProduct');
const prevBtn = document.querySelector('.prev');
const nextBtn = document.querySelector('.next');


let currentPosition = 0;

const slide = (direction) => {
  const sliderWidth = sliderTrack.offsetWidth; 
  const totalWidth = sliderTrack.scrollWidth;

  const moveDistance = sliderWidth; 

  if (direction === 'next') {
   
    if (currentPosition + moveDistance < totalWidth) {
      currentPosition += moveDistance;
    }
  } else if (direction === 'prev') {
    
    if (currentPosition - moveDistance >= 0) {
      currentPosition -= moveDistance;
    }
  }

 
  sliderTrack.style.transform = `translateX(-${currentPosition}px)`;
};


prevBtn.addEventListener('click', () => slide('prev'));
nextBtn.addEventListener('click', () => slide('next'));


