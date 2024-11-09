/**
 * Main - Front Pages
 */
'use strict';

(function () {
  const nav = document.querySelector('.layout-navbar'),
    heroAnimation = document.getElementById('hero-animation'),
    animationImg = document.querySelectorAll('.hero-dashboard-img'),
    animationElements = document.querySelectorAll('.hero-elements-img'),
    swiperLogos = document.getElementById('swiper-clients-logos'),
    swiperReviews = document.getElementById('swiper-reviews'),
    ReviewsPreviousBtn = document.getElementById('reviews-previous-btn'),
    ReviewsNextBtn = document.getElementById('reviews-next-btn'),
    ReviewsSliderPrev = document.querySelector('.swiper-button-prev'),
    ReviewsSliderNext = document.querySelector('.swiper-button-next'),
    priceDurationToggler = document.querySelector('.price-duration-toggler'),
    priceMonthlyList = [].slice.call(document.querySelectorAll('.price-monthly')),
    priceYearlyList = [].slice.call(document.querySelectorAll('.price-yearly'));

  // Hero
  const mediaQueryXL = 1200;
  const width = screen.width;

  if (width >= mediaQueryXL && heroAnimation) {
    heroAnimation.addEventListener('mousemove', function parallax(e) {
      animationElements.forEach(layer => {
        layer.style.transform = 'translateZ(1rem)';
      });
      animationImg.forEach(layer => {
        const x = (window.innerWidth - e.pageX * 2) / 100;
        const y = (window.innerHeight - e.pageY * 2) / 100;
        layer.style.transform = `perspective(1200px) rotateX(${y}deg) rotateY(${x}deg) scale3d(1, 1, 1)`;
      });
    });

    nav.addEventListener('mousemove', function parallax(e) {
      animationElements.forEach(layer => {
        layer.style.transform = 'translateZ(1rem)';
      });
      animationImg.forEach(layer => {
        const x = (window.innerWidth - e.pageX * 2) / 100;
        const y = (window.innerHeight - e.pageY * 2) / 100;
        layer.style.transform = `perspective(1200px) rotateX(${y}deg) rotateY(${x}deg) scale3d(1, 1, 1)`;
      });
    });

    heroAnimation.addEventListener('mouseout', function () {
      animationElements.forEach(layer => {
        layer.style.transform = 'translateZ(0)';
      });
      animationImg.forEach(layer => {
        layer.style.transform = 'perspective(1200px) scale(1) rotateX(0) rotateY(0)';
      });
    });
  }

  // swiper carousel
  if (swiperReviews) {
    new Swiper(swiperReviews, {
      slidesPerView: 1,
      spaceBetween: 0,
      grabCursor: true,
      autoplay: {
        delay: 3000,
        disableOnInteraction: false
      },
      loop: true,
      loopAdditionalSlides: 1,
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev'
      },
      breakpoints: {
        1200: {
          slidesPerView: 3
        },
        992: {
          slidesPerView: 2
        }
      }
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    // Reviews slider next and previous
    if (ReviewsNextBtn && ReviewsSliderNext) {
      ReviewsNextBtn.addEventListener('click', function () {
        ReviewsSliderNext.click();
      });
    }

    if (ReviewsPreviousBtn && ReviewsSliderPrev) {
      ReviewsPreviousBtn.addEventListener('click', function () {
        ReviewsSliderPrev.click();
      });
    }

    // Review client logo
    if (swiperLogos) {
      new Swiper(swiperLogos, {
        slidesPerView: 2,
        autoplay: {
          delay: 3000,
          disableOnInteraction: false
        },
        breakpoints: {
          992: {
            slidesPerView: 5
          },
          768: {
            slidesPerView: 3
          }
        }
      });
    }

    // Pricing Plans
    function togglePrice() {
      if (priceDurationToggler && priceDurationToggler.checked) {
        priceYearlyList.forEach(yearEl => yearEl.classList.remove('d-none'));
        priceMonthlyList.forEach(monthEl => monthEl.classList.add('d-none'));
      } else {
        priceYearlyList.forEach(yearEl => yearEl.classList.add('d-none'));
        priceMonthlyList.forEach(monthEl => monthEl.classList.remove('d-none'));
      }
    }

    if (priceDurationToggler) {
      togglePrice();
      priceDurationToggler.addEventListener('change', togglePrice);
    }
  });
})();
