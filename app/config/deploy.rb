set :application, "VelaForFun"
set :domain,      "151.236.62.144"
set :deploy_to,   "/var/www/"
set :app_path,    "app"
set :user, "root"

role  :web,           domain
role  :app,           domain, :primary => true

set   :use_sudo,      false
set   :keep_releases, 3



set   :scm,           :git
set   :repository,    "/var/www/velaforfun/"
set   :deploy_via,    :copy
