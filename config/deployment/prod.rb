#!/bin/env ruby
# encoding: utf-8

# SCM OPTIONS
# ==========================
set :scm_user               , "ssh_prod_cosmos" # optional
set :scm_password           , "TacVep5Octy" # optional

# Destination
# ==========================
set :domain                 , "prod.bne.fr"
#server "$IP_SERVER_DEV"    , :app, :web, :primary => true
set :branch                 , "master"
set :deploy_to              , "/var/www/prod"

# ROLES
role :web                   , domain                         # Your HTTP server, Apache/etc
role :app                   , domain                         # This may be the same as your `Web` server
role :db                    , domain, :primary => true       # This is where Symfony2 migrations will run

# Fichiers ou dossiers à préserver (décommenter si nécessaire)
# ==========================
#set :linked_dirs                , %w{dossier1 dossier2 dossier3}

# TASKS
# ==========================
namespace :prepare do
  desc "Task to prepare parameters file of the symfony configuration."
  task :configuration do
    run "source {env_dir}/demoapi/.env"
    run "chmod -R 750 #{latest_release}/bin/*"
    run "cd #{latest_release} && make prepare-persistence"
    run "rm -rf #{latest_release}/web/uploads"
    run "mkdir -p #{shared_path}/web/uploads"
    run "ln -s #{shared_path}/web/uploads #{latest_release}/web/uploads"
  end
end

# Finalisation du déploiement
# ==========================
after "deploy:setup", "deploy:create_release_dir"
#before "deploy:update", "deploy:create_release_dir"
after "deploy:restart", "prepare:configuration"
after "prepare:configuration", "deploy:cleanup"
