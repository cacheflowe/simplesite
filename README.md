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

**Create the Heroku php app**

* `heroku create`
* `git push heroku master`
* Manually set php buildpack: `heroku config:add BUILDPACK_URL=https://github.com/heroku/heroku-buildpack-php` - Maybe not needed?
* Add index.php

**Setup Apache**

Add to hosts:
`127.0.0.1 localhost.simplesite.com`

Add to vhosts:
```
<VirtualHost *:80>
    ServerName localhost.simplesite.com
    DocumentRoot /Users/user/Documents/local_path/simplesite
</VirtualHost>
```

# Special functions

* Add `?notDev` to load production js/css



# TODO

* support multiple layouts?
* make BaseArea way more simple. use composition to include embetter, imagexpander, etc
