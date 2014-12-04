var baseUrl,
    baseUrlAdmin,
    imagesPath,
    stepNb = 1,
    userAdminLogin = 'admin@example.org',
    userAdminPass = 'testtest';

if (!casper.cli.has('base-url')) {
    casper.echo('You need to specify the --base-url parameter');
    casper.exit(1);
}

baseUrl = casper.cli.get('base-url');
if (baseUrl.substring(0, 4) != "http") {
    baseUrl = 'http://' + baseUrl;
}
baseUrlAdmin = baseUrl + '/admin.php';
casper.echo('Testing: ' + baseUrl);
casper.echo('Admin Testing: ' + baseUrlAdmin);

if (casper.cli.has('images-path')) {
    imagesPath = casper.cli.get('images-path');
} else {
    imagesPath = 'build/casper/screenshots';
}

if (!casper.cli.has('disable-timeout')) {
    casper.options.waitTimeout = 15000;
    casper.options.stepTimeout = 31000;

    casper.on('timeout', function() {
        this.exit(1);
    });
    casper.on('waitFor.timeout', function() {
        this.exit(1);
    });
}

casper.on('step.complete', function(location, settings) {
    this.capture(imagesPath + '/' + suiteName +  '/'  + stepNb + '.png');
    stepNb++;
});

casper.echo('[Bootstrap] Configured test suite.');
casper.test.done();
