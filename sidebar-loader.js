// sidebar-loader.js
function loadSidebar() {
    // Crear contenedor para el sidebar
    const sidebarContainer = document.createElement('div');
    sidebarContainer.id = 'sidebar-container';
    
    // Cargar el contenido del sidebar
    fetch('sidebar.html')
        .then(response => response.text())
        .then(data => {
            sidebarContainer.innerHTML = data;
            document.body.prepend(sidebarContainer);
            
            // Ajustar el contenido principal
            const mainContent = document.querySelector('.main-content');
            if (mainContent) {
                mainContent.style.marginLeft = '80px';
                
                // Ajustar para móviles
                if (window.innerWidth <= 768) {
                    mainContent.style.marginLeft = '0';
                }
            }
        })
        .catch(error => console.error('Error loading sidebar:', error));
}

// Llamar a la función cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', loadSidebar);
} else {
    loadSidebar();
}