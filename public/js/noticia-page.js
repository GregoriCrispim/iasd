// Notícia page sharing functionality
document.addEventListener('DOMContentLoaded', function() {
    const currentPageUrl = encodeURIComponent(window.location.href);
    const pageTitle = encodeURIComponent(document.querySelector('h1')?.textContent || 'Notícia');

    // Facebook share
    window.compartilharFacebook = function() {
        const shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${currentPageUrl}`;
        window.open(shareUrl, '_blank', 'width=600,height=400');
    };

    // WhatsApp share
    window.compartilharWhatsApp = function() {
        const shareUrl = `https://wa.me/?text=${pageTitle}%20-%20${currentPageUrl}`;
        window.open(shareUrl, '_blank');
    };

    // Twitter share
    window.compartilharTwitter = function() {
        const shareUrl = `https://twitter.com/intent/tweet?url=${currentPageUrl}&text=${pageTitle}`;
        window.open(shareUrl, '_blank', 'width=600,height=400');
    };
});
