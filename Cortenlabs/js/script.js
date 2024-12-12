document.addEventListener("DOMContentLoaded", () => {
    // Dynamisch menu laden
    const sidebar = document.querySelector('.sidebar');
    fetch('partials/menu.html')
        .then(response => response.text())
        .then(data => {
            sidebar.innerHTML = data;

            // Voeg eventlisteners toe aan de menu-links
            const links = document.querySelectorAll('.sidebar a');
            const contentPlaceholder = document.getElementById('content-placeholder');

            links.forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();

                    const url = e.target.getAttribute('href');

                    fetch(url)
                        .then(response => response.text())
                        .then(data => {
                            contentPlaceholder.innerHTML = data;

                            // Markeer actieve link
                            links.forEach(l => l.classList.remove('active'));
                            e.target.classList.add('active');

                            // Controleer of contact-form is geladen en voeg eventlistener toe
                            const form = document.getElementById("contact-form");
                            if (form) {
                                console.log("Contactformulier geladen:", form);

                                const feedback = document.getElementById("form-feedback");

                                form.addEventListener("submit", (e) => {
                                    e.preventDefault();
                                    console.log("Submit onderschept!");

                                    const formData = new FormData(form);

                                    fetch(form.action, {
                                        method: "POST",
                                        body: formData,
                                    })
                                        .then(response => response.json())
                                        .then(data => {
                                            console.log("Server response:", data);
                                            if (data.status === "success") {
                                                feedback.textContent = data.message;
                                                feedback.className = "form-feedback";
                                                form.reset();
                                            } else {
                                                feedback.textContent = data.message;
                                                feedback.className = "form-feedback error";
                                            }
                                        })
                                        .catch(error => {
                                            console.error("Fetch error:", error);
                                            feedback.textContent = "Er is een onverwachte fout opgetreden.";
                                            feedback.className = "form-feedback error";
                                        });
                                });
                            } else {
                                console.warn("Contactformulier niet aanwezig op deze pagina.");
                            }
                        })
                        .catch(error => console.error('Error loading content:', error));
                });
            });
        })
        .catch(error => console.error('Error loading menu:', error));
});
