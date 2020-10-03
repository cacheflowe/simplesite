Simplesite
============

A boilerplate .php + .js site framework with friendly URLs, pushState support and SEO-friendliness

**Features:**

- Server-side rendering that switches to fetch() requests (and pushState URL updates) after initial load
- Automatic connection & disposal of Javascript views to main html content
- Automatic js & css includes that mirror the compiled minified Gulp build
  - This allows easy switching between dev & production modes, and easy dropping in of new js/css files
  - ...but has the caveat of *all js/css directories being included in alphabetical order*, with simplesite core files included first
  - The main app.es6.js example ensures that it's not initialized until all scripts are loaded, so we should be good in this regard of .js load order
- Per-page overrides of `head` metadata, with robust defaults and automatic content extraction to prefill metadata per-page
- Basic login authentication for protected pages
- Basic CMS functionality examples
- Image lazy-loading

---

## Setup to use Gulp for minifying css/js)

run `gulp-init.sh`

**Push to your new repo**

* `git push -u origin master`

## Special functions

* Add `?notDev` to load production js/css


---
## TODO

- Figure out Rollup in Gulp for minimized module-based compiling: https://rollupjs.org/guide/en/
  * Look at `terser` and/or `esbuild`
- Extract main nav css/js to make it more modular
- Static publishing
  - How to handle links between pages? Is this already handled?
  - Can AreaModel handle loading an entire static page and just pull out the content area?
- Push loader over if nav drawer is showing
- Add Embetter demo
- Make uploader a legit class & replace "demodesk" view
- Fix square thumbnail in news-listing-view - make it a css responsibility w/section/subsection classes added to body
  - news-listing-view should be in the client implementation, not the core
- Bring back in:
  - Config CMS demo w/grid & config card
- Fix embetter, ShareOut, CacheCart
- Remove Mail.php
- [DONE?] Add ability to create cached responses in AreaModel so we can get pushState and content swapping without hitting the server again
- support multiple layouts?
- Check <head> entires: https://github.com/joshbuchea/HEAD
- Remember scroll position when going back?

Maybe?

- Add paging into RSS view
- Add `/rss` to any page to retrieve rss data source?
- Look at <img loading=lazy> - it's not ready yet



# Sites using SimpleSite

* cacheflowe.com
* ohheckyeah.com
* lazwicky.com
* frakefineart.com
* DBG CMS
* Nike projects
