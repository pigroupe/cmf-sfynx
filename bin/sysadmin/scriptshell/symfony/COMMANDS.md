Available commands:
  help                                  Displays help for a command
  list                                  Lists commands
assetic
  assetic:dump                          Dumps all assets to the filesystem
assets
  assets:install                        Installs bundles web assets under a public web directory
cache
  cache:clear                           Clears the cache
  cache:warmup                          Warms up an empty cache
config
  config:dump-reference                 Dumps default configuration for an extension
container
  container:debug                       Displays current services for an application
debug
  debug:swiftmailer                     Displays current mailers for an application
doctrine
  doctrine:cache:clear-metadata         Clears all metadata cache for an entity manager
  doctrine:cache:clear-query            Clears all query cache for an entity manager
  doctrine:cache:clear-result           Clears result cache for an entity manager
  doctrine:database:create              Creates the configured databases
  doctrine:database:drop                Drops the configured databases
  doctrine:ensure-production-settings   Verify that Doctrine is properly configured for a production environment.
  doctrine:generate:crud                Generates a CRUD based on a Doctrine entity
  doctrine:generate:entities            Generates entity classes and method stubs from your mapping information
  doctrine:generate:entity              Generates a new Doctrine entity inside a bundle
  doctrine:generate:form                Generates a form type class based on a Doctrine entity
  doctrine:mapping:convert              Convert mapping information between supported formats.
  doctrine:mapping:import               Imports mapping information from an existing database
  doctrine:mapping:info                 Shows basic information about all mapped entities
  doctrine:query:dql                    Executes arbitrary DQL directly from the command line.
  doctrine:query:sql                    Executes arbitrary SQL directly from the command line.
  doctrine:schema:create                Executes (or dumps) the SQL needed to generate the database schema
  doctrine:schema:drop                  Executes (or dumps) the SQL needed to drop the current database schema
  doctrine:schema:update                Executes (or dumps) the SQL needed to update the database schema to match the current mapping metadata
  doctrine:schema:validate              Validates the doctrine mapping files
generate
  generate:bundle                       Generates a bundle
  generate:controller                   Generates a controller
  generate:doctrine:crud                Generates a CRUD based on a Doctrine entity
  generate:doctrine:entities            Generates entity classes and method stubs from your mapping information
  generate:doctrine:entity              Generates a new Doctrine entity inside a bundle
  generate:doctrine:form                Generates a form type class based on a Doctrine entity
init
  init:acl                              Mounts ACL tables in the database
router
  router:debug                          Displays current routes for an application
  router:dump-apache                    Dumps all routes as Apache rewrite rules
  router:match                          Helps debug routes by simulating a path info match
server
  server:run                            Runs PHP built-in web server
swiftmailer
  swiftmailer:debug                     Displays current mailers for an application
  swiftmailer:email:send                Send simple email message
  swiftmailer:spool:send                Sends emails from the spool
translation
  translation:update                    Updates the translation file
twig
  twig:lint                             Lints a template and outputs encountered errors