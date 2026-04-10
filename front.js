const header = document.getElementById("siteHeader");
const navToggle = document.getElementById("navToggle");
const mobileNav = document.getElementById("mobileNav");

if (header) {
    const syncHeader = () => {
        header.classList.toggle("is-scrolled", window.scrollY > 12);
    };

    syncHeader();
    window.addEventListener("scroll", syncHeader, { passive: true });
}

if (navToggle && mobileNav) {
    navToggle.addEventListener("click", () => {
        const isOpen = navToggle.classList.toggle("is-open");
        mobileNav.classList.toggle("is-open", isOpen);
        navToggle.setAttribute("aria-expanded", isOpen ? "true" : "false");
    });
}

const revealItems = document.querySelectorAll(".reveal");

if ("IntersectionObserver" in window && revealItems.length) {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add("is-visible");
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });

    revealItems.forEach((item) => observer.observe(item));
} else {
    revealItems.forEach((item) => item.classList.add("is-visible"));
}
