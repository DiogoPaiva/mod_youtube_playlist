document.addEventListener("DOMContentLoaded", function () {
    var ytTriggers = document.querySelectorAll(".lightbox-trigger");
    var ytLightbox = document.getElementById("lightbox");
    var ytCloseBtn = ytLightbox.querySelector(".lightbox-close");

    // Garantir que começa fechado
    ytLightbox.classList.remove("active");

    function openYoutubeLightbox(event) {
        event.preventDefault();
        ytLightbox.classList.add("active");
        disableScroll();
    }

    function closeYoutubeLightbox() {
        ytLightbox.classList.remove("active");
        enableScroll();
    }

    function disableScroll() {
        document.body.style.overflow = "hidden";
        document.body.style.position = "fixed";
        document.body.style.width = "100%";
    }

    function enableScroll() {
        document.body.style.overflow = "";
        document.body.style.position = "";
        document.body.style.width = "";
    }

    for (var i = 0; i < ytTriggers.length; i++) {
        ytTriggers[i].addEventListener("click", openYoutubeLightbox);
    }
    if (ytCloseBtn) ytCloseBtn.addEventListener("click", closeYoutubeLightbox);

    document.addEventListener("click", function (event) {
        if (event.target === ytLightbox) closeYoutubeLightbox();
    });

    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") closeYoutubeLightbox();
    });
});
