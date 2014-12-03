var suiteName = 'login';

casper.start(baseUrlAdmin + '/', function() {
    var url = this.getCurrentUrl();
    this.viewport(1024, 768);

    this.test.assertUrlMatch(baseUrlAdmin + '/login', 'login redirection');
});

casper.run(function() {
    casper.test.done();
});
