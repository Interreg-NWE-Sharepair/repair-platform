## Opensource steps

To opensource a few steps  need to be taken. They will be explained below.

There are 2 options for the next step:
- Make new branch that has no history
`git checkout --orphan branchname`
- Remove `.git` folder and do the next steps __before__ running `git flow init`

Delete all credentials related to us:
- `.env` files (all of them except for .env-example)
- `.htaccess` files, maybe leave an example file
- `fichenbak-versioning.sh`
- __Everything__ from Deployer (hosts.yml, .deploy, bitbucket-pipelines.yml)
- Cleanup `.ddev/config.yaml`
- `worker.sh` files __all of them__
- `cypress.json`
- `config/google-auth.php` remove statik.be
- Search for `statik` and remove/change everything you can find
  - emails containing `@statik.be`
  - Role with the name Statik and everything attached to it


- Finally, Delete this file.
- Initiate `git init` or `git flow init`


