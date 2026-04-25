document.addEventListener("DOMContentLoaded", function() {
    const mainImage = document.querySelector(".main-image");
    const thumbnails = document.querySelectorAll(".small-image");

    thumbnails.forEach(thumb => {
        thumb.addEventListener("click", function() {
            mainImage.src = this.src;

            thumbnails.forEach(t => t.classList.remove("active-thumb"));

            this.classList.add("active-thumb");
        });
    });
});
