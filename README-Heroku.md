# Heroku setup

**Create the Heroku php app**

* `heroku create`
* `git push heroku master`
* Add index.php

Staging:
* `heroku create --remote staging` - create staging server
* `git push staging master` - Push to dev server: https://mysterious-words-xxxxx.herokuapp.com
* `heroku logs -t --remote staging` - Staging logs

**Clean up old, large files**

* `git gc`
* `bfg --strip-blobs-bigger-than 50M`
* `git reflog expire --expire=now --all && git gc --prune=now --aggressive`

**Set up domain**

* Set nameservers on GoDaddy to point to Cloudflare (ex. CHAD.NS.CLOUDFLARE.COM & ROSE.NS.CLOUDFLARE.COM)
* Also set www forwarding for naked domain requests
▸    http://stackoverflow.com/questions/14125175/setup-heroku-and-godaddy

* `heroku domains:add yourdomain.com`
* `heroku domains:add www.yourdomain.com`

▸    Configure your app's DNS provider to point to the DNS Target yourdomain.com.herokudns.com.
▸    Configure your app's DNS provider to point to the DNS Target www.yourdomain.com.herokudns.com.
▸    For help, see https://devcenter.heroku.com/articles/custom-domains

* `heroku ps:resize web=1`
* `heroku ps:resize web=1:Hobby`

SSL w/Cloudflare

* https://support.cloudflare.com/hc/en-us/articles/205893698-Configure-CloudFlare-and-Heroku-over-HTTPS
* `heroku certs:auto:enable`
