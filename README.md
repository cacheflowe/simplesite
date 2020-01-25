Simplesite
============

A boilerplate php (I know) site starter with friendly URLs, pushState support and SEO-friendliness


# TODO

* Fix square thumbnail in news-listing-view - make it a css responsibility w/section/subsection classes added to body
  * news-listing-view should be in the client implementation, not the core
* Move to Gulp vs. Grunt
* Bring back in:
  * Config CMS demo w/grid & config card
* Fix embetter, ShareOut, CacheCart
* Move grunt-includes to simplesite/php ?
* Remove Mail.php
* Add ability to create cached responses in AreaModel so we can get pushState and content swapping without hitting the server again
* support multiple layouts?
* Check <head> entires: https://github.com/joshbuchea/HEAD?utm_source=frontendfocus&utm_medium=email
* Remember scroll position when going back?
* Add paging into RSS view
* Add `/rss` to any page to retrieve rss data source?


# Setup to use Grunt for minifying css/js)

**NPM + Packages for Grunt build**

run `init.sh`

**Push to your new repo**

* `git push -u origin master`

## Heroku setup

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



# Sites using SimpleSite

* cacheflowe.com
* ohheckyeah.com
* lazwicky.com
