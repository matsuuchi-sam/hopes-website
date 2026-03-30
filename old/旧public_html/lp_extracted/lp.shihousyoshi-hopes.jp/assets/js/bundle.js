document.getElementById("header").addEventListener("click", function () {
  this.classList.toggle("toggle");
  // document.getElementById("menu-global_nav").classList.toggle("active");
});

let swipeOption = {
  loop: true,
  effect: "fade",
  autoplay: {
    delay: 4000,
    disableOnInteraction: false,
  },
  speed: 2000,
  // pagination: {
  //   el: ".swiper-pagination",
  //   clickable: true,
  // },
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
})(jQuery);
