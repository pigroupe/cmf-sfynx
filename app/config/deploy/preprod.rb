set :domain,      "www.sfynx.fr"
set :deploy_to,   "<chemin-du-serveur>/preprod"

set :user, "<utilisateur>"
# accept ssh
set :default_run_options, {:pty => true}
set :ssh_options, {:forward_agent => true}

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain                         # This may be the same as your `Web` server
role :db,         domain, :primary => true       # This is where Symfony2 migrations will run

# Deployment strategy
set :branch, tag
set :copy_vendors, false

# REPOSITORY
set :repository,  "https://0c06ed2ccf363b00d7f3878827dfd4e37dedc37b@github.com/pigroupe/cmf-sfynx.git"

# SHARING PATH
set :shared_files,      ["app/config/parameters.yml", "app/config/routing.yml", "web/robots.txt", "resetProjectDataPreprod.sh"]

# VENDORS AND SHARING PATH
set :use_composer,      true
set :update_vendors,    false
set :install_vendors,   true
set :copy_vendors, false

# Symfony2
set :dump_assetic_assets, true
set :interactive_mode, false
set :clear_controllers, false
set :webserver_user,    "www-data"

# Clean deploy releases
set :keep_releases, 5

after "deploy", "sfynxnamespace:reset_data_prod"
after "sfynxnamespace:reset_data", "deploy:cleanup"

# Run logger
logger.level = Logger::MAX_LEVEL
