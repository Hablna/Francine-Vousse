const Encore = require('@symfony/webpack-encore');

Encore
    // Le répertoire de sortie pour les fichiers compilés
    .setOutputPath('public/build/')
    .setPublicPath('/build')

    // Ajouter une entrée pour le fichier JavaScript principal
    .addEntry('app', './assets/app.js')

    // Activer le traitement de SCSS (si nécessaire)
    .enableSassLoader()

    // Générer un fichier manifest pour lier les assets
    .enableVersioning()
    .enableSourceMaps(!Encore.isProduction())

    // Activez le nettoyage du dossier de build avant chaque compilation
    .cleanupOutputBeforeBuild()
;

module.exports = Encore.getWebpackConfig();
