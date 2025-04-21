document.addEventListener("DOMContentLoaded", function () {
    const navLinks = document.querySelectorAll(".nav-link");

    const activePath = localStorage.getItem("activeNavPath");

    navLinks.forEach(link => {
        if (link.getAttribute("href") === activePath) {
            link.classList.add("active");
        }

        link.addEventListener("click", function () {
            localStorage.setItem("activeNavPath", this.getAttribute("href"));
        });
    });
});
