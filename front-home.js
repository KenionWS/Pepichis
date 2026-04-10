if (typeof gsap !== "undefined" && typeof ScrollTrigger !== "undefined") {
    gsap.registerPlugin(ScrollTrigger);

    const h1 = document.getElementById("hero-typed");
    const p = document.getElementById("hero-typed-p");
    const copas = document.getElementById("hero-copas");

    function typeWriter() {
        if (!h1 || !p || !copas) return;
        h1.innerHTML = "";
        p.textContent = "";
        const h1Lines = ["Importamos los vinos", "que nos gusta tomar"];
        const pText = "Los que pedir\u00EDamos si estuvi\u00E9ramos del otro lado de la carta.";
        let lineIndex = 0;
        let charIndex = 0;
        let currentSpan = null;
        const cursor = document.createElement("span");
        cursor.className = "typing-cursor";

        function createSpan() {
            currentSpan = document.createElement("span");
            currentSpan.className = "highlight";
            h1.appendChild(currentSpan);
            currentSpan.appendChild(cursor);
        }

        function typeH1() {
            if (lineIndex >= h1Lines.length) {
                cursor.remove();
                p.appendChild(cursor);
                charIndex = 0;
                setTimeout(typeP, 200);
                return;
            }
            if (charIndex === 0) {
                if (lineIndex > 0) h1.appendChild(document.createElement("br"));
                createSpan();
            }
            const line = h1Lines[lineIndex];
            if (charIndex < line.length) {
                currentSpan.insertBefore(document.createTextNode(line[charIndex]), cursor);
                charIndex++;
                setTimeout(typeH1, 35);
            } else {
                lineIndex++;
                charIndex = 0;
                setTimeout(typeH1, 200);
            }
        }

        function typeP() {
            if (charIndex < pText.length) {
                p.insertBefore(document.createTextNode(pText[charIndex]), cursor);
                charIndex++;
                setTimeout(typeP, 25);
            } else {
                cursor.remove();
                gsap.to(copas, { opacity: 1, y: 0, rotation: 0, duration: 1.2, ease: "power3.out" });
            }
        }

        gsap.set(copas, { opacity: 0, y: 30, rotation: -3 });
        setTimeout(typeH1, 400);
    }

    typeWriter();

    const hero = document.querySelector(".hero");
    if (hero && copas) {
        hero.addEventListener("mousemove", (e) => {
            const rect = e.currentTarget.getBoundingClientRect();
            const x = (e.clientX - rect.left) / rect.width - 0.5;
            const y = (e.clientY - rect.top) / rect.height - 0.5;
            gsap.to(copas, { x: x * 30, y: y * 20, rotation: x * 3, duration: 0.6, ease: "power2.out" });
        });
        hero.addEventListener("mouseleave", () => {
            gsap.to(copas, { x: 0, y: 0, rotation: 0, duration: 0.8, ease: "elastic.out(1, 0.5)" });
        });
    }

    ScrollTrigger.create({
        trigger: ".bottles-sticky",
        start: "top 80%",
        once: true,
        onEnter: () => {
            gsap.to(document.querySelectorAll(".bottle-item"), { opacity: 1, y: 0, duration: 0.6, stagger: 0.08, ease: "power2.out" });
        },
    });

    const producerCards = document.querySelectorAll(".producer-card-container");
    producerCards.forEach((container) => {
        gsap.to(container, {
            scrollTrigger: { trigger: container, start: "top bottom-=80", end: "top center+=50", scrub: 1 },
            opacity: 1,
            y: 0,
            ease: "power2.out",
        });
    });

    function isMobile() { return window.innerWidth <= 1024; }
    let scrollFlipTriggers = [];
    function setupFlipBehavior() {
        scrollFlipTriggers.forEach((st) => st.kill());
        scrollFlipTriggers = [];
        producerCards.forEach((c) => c.classList.remove("flipped"));
        if (isMobile()) {
            producerCards.forEach((container) => {
                const st = ScrollTrigger.create({
                    trigger: container,
                    start: "35% center",
                    end: "bottom top",
                    onEnter: () => container.classList.add("flipped"),
                    onLeaveBack: () => container.classList.remove("flipped"),
                });
                scrollFlipTriggers.push(st);
            });
        }
    }

    producerCards.forEach((container) => {
        container.addEventListener("mouseenter", () => { if (!isMobile()) container.classList.add("flipped"); });
        container.addEventListener("mouseleave", () => { if (!isMobile()) container.classList.remove("flipped"); });
    });

    setupFlipBehavior();
    window.addEventListener("resize", setupFlipBehavior);
}
