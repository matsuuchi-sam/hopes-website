const targetElement = document.querySelectorAll(".animationTarget");
document.addEventListener("scroll", function () {
  for (let i = 0; i < targetElement.length; i++) {
    const getElementDistance =
      targetElement[i].getBoundingClientRect().top +
      targetElement[i].clientHeight * 0.6;
    if (window.innerHeight > getElementDistance) {
      targetElement[i].classList.add("show");
    } else {
      targetElement[i].classList.remove("show");
    }
  }
});

document.getElementById("header").addEventListener("click", function () {
  this.classList.toggle("toggle");
});

let swipeOption = {
  loop: true,
  effect: "fade",
  autoplay: {
    delay: 4000,
    disableOnInteraction: false,
  },
  speed: 2000,
};
new Swiper(".swiper-container", swipeOption);

let scroll = new SmoothScroll('a[href*="#"]', {
  header: "#header",
  speed: 300,
});

window.addEventListener("scroll", function () {
  let scroll = this.document.documentElement.scrollTop;
  if (scroll > 300) {
    this.document.getElementById("header").classList.add("active");
  } else {
    this.document.getElementById("header").classList.remove("active");
  }
});

(function ($) {
  var hash = $(location).attr("hash");
  if (hash) {
    var target = $(hash).offset().top;
    $("html,body").animate({ scrollTop: target }, "slow");
  }

  if ($("body").hasClass("home")) {
    $("#home").addClass("active");
  } else if ($("body").hasClass("about")) {
    $("#about").addClass("active");
  } else if ($("body").hasClass("service")) {
    $("#service").addClass("active");
  } else if ($("body").hasClass("price")) {
    $("#price").addClass("active");
  } else if ($("body").hasClass("profile")) {
    $("#profile").addClass("active");
  }

  $(".contents .item a").hover(
    function () {
      $(this).parent().parent().addClass("active");
    },
    function () {
      $(this).parent().parent().removeClass("active");
    }
  );
})(jQuery);
