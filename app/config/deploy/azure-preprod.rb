set :domain,      "www.sfynx.fr"
set :deploy_to,   "/websites/sfynx/preprod"

set :user, "opendev"

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain                         # This may be the same as your `Web` server
role :db,         domain, :primary => true       # This is where Symfony2 migrations will run

# Deployment strategy
set :branch,      "preprod"

# REPOSITORY
set :repository,  "git@github.com/pigroupe/cmf-sfynx.git"

# SHARING PATH
set :shared_files,      ["app/config/parameters.yml", "app/config/routing.yml", "web/robots.txt", "resetProjectData.sh"]

# Symfony2
set :dump_assetic_assets, true
set :interactive_mode, false
set :clear_controllers, false
set :webserver_user,    "www-data"

# Clean deploy releases
set :keep_releases, 2

# Run migrations before warming the cache
#before "symfony:cache:warmup", "doctrine:migrate"
# Release cleanup

after "deploy", "sfynxnamespace:reset_data"
#after "sfynxnamespace:reset_data", "deploy:cleanup"

# Run logger
logger.level = Logger::MAX_LEVEL