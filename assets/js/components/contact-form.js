// ============================================================
// LibreCSS – Contact Form
// ============================================================

document.addEventListener("DOMContentLoaded", () => {
	const form = document.getElementById("contactForm");
	const successMsg = document.getElementById("cf-success");

	if (!form) return;

	const fields = {
		name: form.querySelector("#cf-name"),
		email: form.querySelector("#cf-email"),
		phone: form.querySelector("#cf-phone"),
		message: form.querySelector("#cf-message"),
		privacy: form.querySelector("#cf-privacy"),
	};

	function showError(input, message) {
		const errorEl = input.parentElement.querySelector(".lib-contact-error");
		if (errorEl) {
			errorEl.innerText = message;
			errorEl.style.display = "block";
		}
	}

	function clearError(input) {
		const errorEl = input.parentElement.querySelector(".lib-contact-error");
		if (errorEl) {
			errorEl.innerText = "";
			errorEl.style.display = "none";
		}
	}

	fields.name.addEventListener("input", () => {
		fields.name.value.trim().length < 2
			? showError(fields.name, "Bitte einen gültigen Namen eingeben.")
			: clearError(fields.name);
	});

	fields.email.addEventListener("input", () => {
		const valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(fields.email.value);
		!valid
			? showError(fields.email, "Bitte eine gültige E-Mail eingeben.")
			: clearError(fields.email);
	});

	fields.message.addEventListener("input", () => {
		fields.message.value.trim().length < 10
			? showError(
				fields.message,
				"Bitte eine Nachricht (mind. 10 Zeichen) eingeben.",
			)
			: clearError(fields.message);
	});

	form.addEventListener("submit", async (e) => {
		e.preventDefault();
		successMsg.style.display = "none";

		let valid = true;

		if (fields.name.value.trim().length < 2) {
			showError(fields.name, "Bitte einen gültigen Namen eingeben.");
			valid = false;
		}

		if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(fields.email.value)) {
			showError(fields.email, "Bitte eine gültige E-Mail eingeben.");
			valid = false;
		}

		if (fields.message.value.trim().length < 10) {
			showError(
				fields.message,
				"Bitte eine Nachricht (mind. 10 Zeichen) eingeben.",
			);
			valid = false;
		}

		if (!fields.privacy.checked) {
			alert("Bitte akzeptieren Sie die Datenschutzerklärung.");
			valid = false;
		}

		const turnstileToken = form.querySelector(
			'input[name="cf-turnstile-response"]',
		)?.value;
		if (!turnstileToken) {
			alert("Bitte Captcha bestätigen.");
			valid = false;
		}

		if (!valid) return;

		const payload = {
			name: fields.name.value,
			email: fields.email.value,
			phone: fields.phone.value,
			message: fields.message.value,
			privacy: fields.privacy.checked ? "yes" : "no",
			turnstile: turnstileToken,
		};

		try {
			const response = await fetch("contact.php", {
				method: "POST",
				headers: { "Content-Type": "application/json" },
				body: JSON.stringify(payload),
			});

			const result = await response.json();

			if (result.success) {
				successMsg.style.display = "block";
				form.reset();
				turnstile.reset();
			} else {
				alert(result.error || "Fehler beim Absenden.");
			}
		} catch (err) {
			alert("Serverfehler: " + err.message);
		}
	});
});

