(function() {
  // globals
  const header__burger = document.querySelector(".header__burger"),
    header__nav = document.querySelector(".header__nav"),
    body = document.querySelector("body"),
    WPI = document.querySelector("#posts");

  // event listeners
  header__burger.addEventListener("click", function() {
    this.classList.toggle("active");
    header__nav.classList.toggle("open");
    body.classList.toggle("overflow-hidden");
  });

  // API
})();
