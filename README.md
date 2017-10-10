Simplesite
============

A boilerplate php (I know) site starter with friendly URLs, pushState support and SEO-friendliness

# Setup

**NPM + Packages for Grunt build**

* `npm init`
* `npm i grunt --save-dev`
* `npm i -g grunt-cli --save-dev`
* `npm i grunt-contrib-uglify --save-dev`
* `npm i grunt-contrib-cssmin --save-dev`
* `npm i grunt-postcss pixrem autoprefixer cssnano --save-dev`
* `npm i grunt-babel babel-preset-es2015 --save-dev`
* `npm i grunt-contrib-clean --save-dev`
* `npm i grunt-contrib-copy --save-dev`

**Push to your new repo**

* `git push -u origin master`

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


# Special functions

* Add `?notDev` to load production js/css



# TODO

* Remove Mail.php
* support multiple layouts?
* make BaseArea way more simple. use composition to include embetter, imagexpander, etc
