// ============================================================
// LibreCSS – Navbar
// ============================================================

function initNavbar() {
	const navbar = document.querySelector("header.lib-navbar");
	const toggle = document.querySelector(".lib-navbar-toggle");
	const menu = document.querySelector(".lib-navbar-menu");
	const links = document.querySelectorAll(".lib-navbar-menu a");

	if (!navbar) return;

	if (toggle && menu) {
		toggle.addEventListener("click", () => {
			toggle.classList.toggle("active");
			menu.classList.toggle("active");
		});
	}

	links.forEach((link) => {
		link.addEventListener("click", () => {
			menu?.classList.remove("active");
			toggle?.classList.remove("active");
		});
	});

	const currentPath = window.location.pathname.split("/").pop();
	links.forEach((link) => {
		if (link.getAttribute("href") === currentPath) {
			link.classList.add("active");
		}
	});

	function updateNavbarState() {
		if (window.scrollY > 8) {
			navbar.classList.add("scrolled", "shrink");
		} else {
			navbar.classList.remove("scrolled", "shrink");
		}
	}

	updateNavbarState();
	window.addEventListener("scroll", updateNavbarState, { passive: true });
}

if (document.readyState === "loading") {
	document.addEventListener("DOMContentLoaded", initNavbar);
} else {
	initNavbar();
}

