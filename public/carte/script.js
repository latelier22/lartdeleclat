function copyLink() {
  navigator.clipboard.writeText(window.location.href)
    .then(function () {
      alert('Lien copié');
    });
}

function shareCard() {
  if (navigator.share) {
    navigator.share({
      title: 'Jérémy Martin - L’art de l’éclat',
      text: 'Carte de visite digitale L’art de l’éclat - Expert detailing automobile à Trévé',
      url: window.location.href
    });
  } else {
    copyLink();
  }
}