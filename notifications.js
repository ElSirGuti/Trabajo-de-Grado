function updateNotificationBadge() {
    fetch('get_notifications_count.php')
        .then(response => response.json())
        .then(data => {
            const badge = document.querySelector('.notification-badge');
            const notificationLink = document.querySelector('.notification-item a');
            if (data.count > 0) {
                if (!badge) {
                    const newBadge = document.createElement('span');
                    newBadge.className = 'notification-badge';
                    newBadge.textContent = data.count;
                    notificationLink.appendChild(newBadge);
                } else {
                    badge.textContent = data.count;
                }
            } else if (badge) {
                badge.remove();
            }
        });
}

// Llama a la función inicialmente y establece el intervalo
updateNotificationBadge();
setInterval(updateNotificationBadge, 300000); // 5 minutos