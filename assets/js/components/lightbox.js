// ============================================================
// LibreCSS – Lightbox
// ============================================================

document.addEventListener("DOMContentLoaded", () => {
	const lightbox = document.getElementById("libLightbox");
	const lightboxImg = document.querySelector(".lib-lightbox-image");
	const closeBtn = document.querySelector(".lib-lightbox-close");
	const prevBtn = document.querySelector(".lib-lightbox-prev");
	const nextBtn = document.querySelector(".lib-lightbox-next");
	const lightboxImages = document.querySelectorAll(".lib-image-grid .item img");

	let currentIndex = 0;

	function openLightbox(index) {
		currentIndex = index;
		const src = lightboxImages[index].src;
		lightboxImg.src = src;
		lightbox.classList.add("active");
	}

	function closeLightbox() {
		lightbox.classList.remove("active");
	}

	function showPrev() {
		currentIndex =
			(currentIndex - 1 + lightboxImages.length) % lightboxImages.length;
		openLightbox(currentIndex);
	}

	function showNext() {
		currentIndex = (currentIndex + 1) % lightboxImages.length;
		openLightbox(currentIndex);
	}

	lightboxImages.forEach((img, index) => {
		img.addEventListener("click", () => openLightbox(index));
	});

	if (closeBtn) closeBtn.addEventListener("click", closeLightbox);
	if (prevBtn) prevBtn.addEventListener("click", showPrev);
	if (nextBtn) nextBtn.addEventListener("click", showNext);

	lightbox.addEventListener("click", (e) => {
		if (e.target === lightbox) closeLightbox();
	});

	document.addEventListener("keydown", (e) => {
		if (!lightbox.classList.contains("active")) return;

		switch (e.key) {
			case "Escape":
				closeLightbox();
				break;
			case "ArrowLeft":
				showPrev();
				break;
			case "ArrowRight":
				showNext();
				break;
		}
	});
});

