Site Starter
============

A boilerplate php (I know) site starter with friendly URLs, pushState support and SEO-friendliness

# Setup

**NPM + Packages**

* `npm init`
* `npm i grunt --save-dev`
* `npm i -g grunt-cli --save-dev`
* `npm i grunt-contrib-uglify --save-dev`
* `npm i grunt-contrib-cssmin --save-dev`
* `npm i grunt-postcss pixrem autoprefixer cssnano --save-dev`
* `npm i grunt-babel babel-preset-es2015 --save-dev`
* `npm i grunt-contrib-clean --save-dev`
* `npm i grunt-contrib-copy --save-dev`


**Create the Heroku app**

* `heroku create`
* `git push heroku master`
* Manually set php buildpack: `heroku config:add BUILDPACK_URL=https://github.com/heroku/heroku-buildpack-php` - Maybe not needed?
* Add index.php


TODO
============
