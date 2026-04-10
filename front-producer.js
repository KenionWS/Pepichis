if (typeof gsap !== "undefined" && typeof ScrollTrigger !== "undefined") {
    gsap.registerPlugin(ScrollTrigger);

    document.querySelectorAll(".producer-photo-wrapper, .breadcrumb, .producer-region-tag, .producer-name, .producer-description, .producer-meta").forEach((el, i) => {
        gsap.from(el, { opacity: 0, y: 40, duration: 1, delay: 0.2 + i * 0.15, ease: "power3.out" });
    });

    const hero = document.querySelector(".producer-hero");
    const photoWrapper = document.querySelector(".producer-photo-wrapper");
    if (hero && photoWrapper) {
        hero.addEventListener("mousemove", (e) => {
            const rect = e.currentTarget.getBoundingClientRect();
            const x = (e.clientX - rect.left) / rect.width - 0.5;
            const y = (e.clientY - rect.top) / rect.height - 0.5;
            gsap.to(photoWrapper, { x: x * 15, y: y * 10, duration: 0.6, ease: "power2.out" });
        });
        hero.addEventListener("mouseleave", () => {
            gsap.to(photoWrapper, { x: 0, y: 0, duration: 0.8, ease: "elastic.out(1, 0.5)" });
        });
    }

    document.querySelectorAll(".wine-scattered-item").forEach((item, i) => {
        gsap.to(item, {
            scrollTrigger: { trigger: ".wines-section", start: "top center+=100", end: "top center-=100", scrub: false, toggleActions: "play none none reverse" },
            opacity: 1,
            y: 0,
            duration: 0.8,
            delay: i * 0.15,
            ease: "power3.out",
        });
    });

    document.querySelectorAll(".mancha").forEach((mancha, i) => {
        gsap.to(mancha, {
            scrollTrigger: { trigger: ".wines-section", start: "top bottom", end: "bottom top", scrub: 1 },
            y: i % 2 === 0 ? -40 : 40,
            x: i % 3 === 0 ? 20 : -20,
            scale: 1 + i * 0.03,
            ease: "none",
        });
    });

    const scattered = document.querySelector(".wines-scattered");
    const dotsContainer = document.getElementById("winesSliderDots");
    const items = document.querySelectorAll(".wine-scattered-item");
    if (scattered && dotsContainer && items.length) {
        const totalSlides = items.length;
        const isMobile = () => window.innerWidth <= 600;
        const getSlideOffset = (index) => items[index] ? items[index].offsetLeft : 0;
        const buildDots = () => {
            dotsContainer.innerHTML = "";
            for (let i = 0; i < totalSlides; i++) {
                const dot = document.createElement("button");
                dot.className = "wines-slider-dot" + (i === 0 ? " active" : "");
                dot.setAttribute("aria-label", "Slide " + (i + 1));
                dot.addEventListener("click", () => {
                    scattered.scrollTo({ left: getSlideOffset(i), behavior: "smooth" });
                });
                dotsContainer.appendChild(dot);
            }
        };
        const updateDots = () => {
            if (!isMobile()) return;
            let activeIndex = 0;
            let closestDistance = Number.POSITIVE_INFINITY;

            items.forEach((item, index) => {
                const distance = Math.abs(item.offsetLeft - scattered.scrollLeft);
                if (distance < closestDistance) {
                    closestDistance = distance;
                    activeIndex = index;
                }
            });

            dotsContainer.querySelectorAll(".wines-slider-dot").forEach((dot, i) => dot.classList.toggle("active", i === activeIndex));
        };
        if (isMobile()) buildDots();
        scattered.addEventListener("scroll", updateDots, { passive: true });
        window.addEventListener("resize", () => {
            if (isMobile()) {
                if (dotsContainer.children.length === 0) buildDots();
            } else {
                dotsContainer.innerHTML = "";
            }
        });
    }
}
