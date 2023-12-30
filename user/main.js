// Sản phẩm mới
$('.sliders-newsp').slick({
    dots: false,
    infinite: true,
    speed: 500,
    fade: true,
    cssEase: 'linear',
    draggable: false,
    prevArrow:"<button type='button' class='slick-prev pull-left'><i class='fa fa-angle-left' aria-hidden='true'></i></button>",
    nextArrow:"<button type='button' class='slick-next pull-right'><i class='fa fa-angle-right' aria-hidden='true'></i></button>"
});

function autoPlay_newsp() {
    setInterval(function() {
      $('.sliders-newsp').slick('slickNext');
    }, 4000);
}
  
autoPlay_newsp();

document.addEventListener("DOMContentLoaded", function() {
  var element = document.querySelector("#newsp");
  element.classList.add("animate");
});

// Sản phẩm hot + new
var btnhot = document.querySelector('#btnhot');
var btnnew = document.querySelector('#btnnew');
var slidershot = document.querySelector('.sliders-hot');
var slidersnew = document.querySelector('.sliders-new');
// Hot
$('.sliders-hot').slick({
  infinite: true,
  slidesToShow: 4,
  slidesToScroll: 1,
  prevArrow:"<button type='button' class='slick-prev pull-left'><i class='fa fa-angle-left' aria-hidden='true'></i></button>",
  nextArrow:"<button type='button' class='slick-next pull-right'><i class='fa fa-angle-right' aria-hidden='true'></i></button>",
  responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 2
      }
    },
    {
      breakpoint: 768,
      settings: {
        slidesToShow: 2
      }
    }
  ]
});

function autoPlay_hot() {
  setInterval(function() {
    $('.sliders-hot').slick('slickNext');
  }, 3000);
}

autoPlay_hot();

btnhot.addEventListener('click', function() {
  slidershot.classList.remove('open');
});
btnhot.addEventListener('click', function() {
  slidersnew.classList.remove('open');
});
btnhot.addEventListener('click', function() {
  btnhot.classList.add('change');
});
btnhot.addEventListener('click', function() {
  btnnew.classList.remove('change');
});

// New
$('.sliders-new').slick({
  infinite: true,
  slidesToShow: 4,
  slidesToScroll: 1,
  prevArrow:"<button type='button' class='slick-prev pull-left'><i class='fa fa-angle-left' aria-hidden='true'></i></button>",
  nextArrow:"<button type='button' class='slick-next pull-right'><i class='fa fa-angle-right' aria-hidden='true'></i></button>",
  responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 2
      }
    },
    {
      breakpoint: 768,
      settings: {
        slidesToShow: 2
      }
    }
  ]
});

function autoPlay_new() {
  setInterval(function() {
    $('.sliders-new').slick('slickNext');
  }, 3000);
}

autoPlay_new();

btnnew.addEventListener('click', function() {
  slidershot.classList.add('open');
});
btnnew.addEventListener('click', function() {
  slidersnew.classList.add('open');
});
btnnew.addEventListener('click', function() {
  btnnew.classList.add('change');
});
btnnew.addEventListener('click', function() {
  btnhot.classList.remove('change');
});

document.addEventListener("DOMContentLoaded", function() {
  var element = document.querySelector('#hotnew');
  element.classList.add('animate')
})

// Review
$('.avt-review').slick({
  slidesToShow: 5,
  slidesToScroll: 1,
  asNavFor: '.item-review',
  dots: false,
  centerMode: true,
  focusOnSelect: true,
  prevArrow: "<button type='button' class='slick-prev pull-left'><i class='fa fa-angle-left' aria-hidden='true'></i></button>",
  nextArrow: "<button type='button' class='slick-next pull-right'><i class='fa fa-angle-right' aria-hidden='true'></i></button>",
  responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 1
      }
    },
    {
      breakpoint: 768,
      settings: {
        slidesToShow: 1
      }
    }
  ]
});
$('.item-review').slick({
  slidesToShow: 1,
  slidesToScroll: 1,
  arrows: false,
  fade: true,
  asNavFor: '.avt-review'
});
$('.avt-review').on('init beforeChange afterChange', function(event, slick, currentSlide, nextSlide) {
  var $slides = $(slick.$slides);
  var $currentSlide = $slides.eq(currentSlide);
  var $nextSlide = $slides.eq(nextSlide);
  
  $slides.find('img').css('transform', 'scale(1)');
  
  if (event.type === 'beforeChange') {
    $nextSlide.find('img').css('transform', 'scale(1.3)'); 
  } else if (event.type === 'afterChange') {
    $currentSlide.find('img').css('transform', 'scale(1.3)');
  }
});

function autoPlay_review() {
  setInterval(function() {
    $('.avt-review').slick('slickNext');
  }, 3000);
}

autoPlay_review();

// Cart
var btncart=document.querySelector('.cart');
var boxcart=document.querySelector('.box-cart');

btncart.addEventListener('click', function() {
  if (boxcart.classList.contains('open')) {
    boxcart.classList.remove('open');
  } else {
    boxcart.classList.add('open');
  }
});

var btndetails=document.querySelectorAll('.btn-detail');
var btnpays=document.querySelectorAll('.btn-pay');
for (const btndetail of btndetails) {
  btndetail.addEventListener('click', function() {
    window.location.href="/CuoiKiWeb/user/handle/info.php"
  });
};
for (const btnpay of btnpays) {
  btnpay.addEventListener('click', function() {
    window.location.href="/CuoiKiWeb/user/handle/info.php?numberpay=change";
  });
};