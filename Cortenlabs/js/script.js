document.addEventListener("DOMContentLoaded", () => {
    // Dynamisch menu laden
    const sidebar = document.querySelector('.sidebar');
    fetch('menu.html')
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
                        })
                        .catch(error => console.error('Error loading content:', error));
                });
            });
        })
        .catch(error => console.error('Error loading menu:', error));
});
