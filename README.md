Simplesite
============

A boilerplate php (I know) site starter with friendly URLs, pushState support and SEO-friendliness


# TODO

- Static publishing
- Switch to Gulp from Grunt
- Add Public Sans as default custom font - remove Poppins

* Fix square thumbnail in news-listing-view - make it a css responsibility w/section/subsection classes added to body
  * news-listing-view should be in the client implementation, not the core
* Bring back in:
  * Config CMS demo w/grid & config card
* Fix embetter, ShareOut, CacheCart
* Move grunt-includes to simplesite/php ?
* Remove Mail.php
* [DONE?] Add ability to create cached responses in AreaModel so we can get pushState and content swapping without hitting the server again
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

# Special functions

* Add `?notDev` to load production js/css



# Sites using SimpleSite

* cacheflowe.com
* ohheckyeah.com
* lazwicky.com
* frakefineart.com
* DBG CMS
* Nike projects
