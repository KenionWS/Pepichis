(() => {
    const headerEl = document.querySelector("header");
    let lastScrolled = false;

    if (headerEl) {
        window.addEventListener("scroll", () => {
            const scrolled = window.scrollY > 50;
            if (scrolled !== lastScrolled) {
                lastScrolled = scrolled;
                headerEl.classList.toggle("header-scrolled", scrolled);
            }
        }, { passive: true });
    }

    document.querySelectorAll('a[href*="#"]').forEach((anchor) => {
        anchor.addEventListener("click", function (e) {
            const href = this.getAttribute("href");
            if (!href) return;

            const hashIndex = href.indexOf("#");
            const hash = hashIndex >= 0 ? href.slice(hashIndex) : "";
            const target = hash ? document.querySelector(hash) : null;
            const samePage = hashIndex === 0 || href.startsWith(window.location.pathname);

            if (target && samePage) {
                e.preventDefault();
                const top = target.getBoundingClientRect().top + window.scrollY - 80;
                window.scrollTo({ top, behavior: "smooth" });
            }
        });
    });

    const hamburger = document.getElementById("hamburgerBtn");
    const mobileMenu = document.getElementById("mobileMenu");
    if (hamburger && mobileMenu) {
        hamburger.addEventListener("click", () => {
            hamburger.classList.toggle("active");
            mobileMenu.classList.toggle("active");
            document.body.style.overflow = mobileMenu.classList.contains("active") ? "hidden" : "";
        });

        document.querySelectorAll(".mobile-nav-links a").forEach((link) => {
            link.addEventListener("click", () => {
                hamburger.classList.remove("active");
                mobileMenu.classList.remove("active");
                document.body.style.overflow = "";
            });
        });
    }
})();
