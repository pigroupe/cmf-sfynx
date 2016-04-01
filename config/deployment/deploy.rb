#MULTISTAGE CONFIGURATION
set :stages, %w(development preprod prod)
set :default_stage, "development"
set :stage_dir, 'config/deployment'
require 'capistrano/ext/multistage'

# REQUIRED VARIABLES
# ==========================
set :application            , "bne"
set :www_dir                , "www/"
set :log_path               , "{www_dir}app/logs"
set :upload_path            , "{www_dir}web/uploads"
set :cache_path             , "{www_dir}app/cache"
set :env_dir                , "config/docker/env/symfony"

# SCM OPTIONS
# ==========================
set :repository             , "git@git.alterway.fr/symfony2/bne-alterway.git"
set :scm                    , :git
#set :git_shallow_clone     , 1
#set :scm_verbose           , true

# DESTINATION
# ==========================
set :use_sudo               , false

# TASKS
# ==========================
namespace :deploy do
  task :create_release_dir, :except => {:no_release => true} do
    run "mkdir -p #{fetch :releases_path}"
  end
end

# STRATEGY
# ==========================

# CAPISTRANO OPTIONS
set :keep_releases          , 5
set :copy_cache             , true
set :deploy_via             , :copy
set :copy_exclude           , [".git", ".gitignore", "Vagrantfile"]
set :copy_remote_dir        , "/tmp"
set :copy_compression       , :gzip

# VENDOR
set :copy_vendors           , true
set :update_vendors         , true
set :install_vendors        , true

# SHARING PATH
set :app_symlinks           , [log_path] # dirs that need to remain the same between deploys
set :shared_children        , [log_path]
set :shared_files           , ["{www_dir}web/robots.txt"]
set :linked_dirs            , %w{log_path}

# PERMISSIONS
set :writable_dirs          , [cache_path, log_path, upload_path]
set :permission_method      , :acl

# pas de public/images, public/css...
set :normalize_asset_timestamps, false # pas de public/images, public/css...

# Accept ssh
ssh_options[:forward_agent] = true
default_run_options[:pty]   = true
set :default_run_options    , {:pty => true}
