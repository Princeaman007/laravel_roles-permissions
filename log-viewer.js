// Sauvegardez dans un fichier log-viewer.js
fetch('/debug-logs')
  .then(response => response.text())
  .then(logs => {
    console.log(logs);
    // Ou affichez les logs dans une interface utilisateur
  })
  .catch(error => console.error('Erreur lors de la récupération des logs:', error));