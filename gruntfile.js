module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

    /*
    copy: {
      main: {
        src: 'index-src.html',
        dest: 'index.html',
        options: {
          process: function (content, srcpath) {
            return content.replace("<!-- start.css -->", '<link rel="stylesheet" href="css/app.min.css">\n<!-- Source:')
                          .replace("<!-- end.css -->", 'end source -->')
                          .replace("<!-- start.js -->", '<script src="js/app.min.js"></script>\n<!-- Source:')
                          .replace("<!-- end.js -->", 'end source -->');
          }
        }
      }
    },
    */

    babel: {
        options: {
            sourceMap: false,
            presets: ['es2015'],
            minified: false,
            compact: false
        },
        dist: {
            files: [{
                expand: true,
                cwd: 'js',
                src: ['**/*.es6.js'],
                dest: 'js/min',
                ext: '.js'
            }, {
                expand: true,
                cwd: 'simplesite',
                src: ['**/*.es6.js'],
                dest: 'js/min/simplesite',
                ext: '.js'
            }]

        }
    },

    uglify: {
      options: {
        banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n',
        mangle: false,
        compress: false,
        beautify: false,
        report: 'min'
      },
      build_site: {
        files: {
          'js/app.min.js': [
              "simplesite/js/vendor/embetter.js",
              "simplesite/js/vendor/easy-scroll.js",
              "simplesite/js/vendor/fetch.js",
              "simplesite/js/vendor/page.js",
              "js/min/simplesite/js/area-model.js",
              "js/min/simplesite/js/base-view.js",
              "js/min/app/views/area-common.js",
              "js/min/app/app.js"
            ]
        }
      }
    },

    cssmin: {
      options: {
        banner: null, //'/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n',
        report: 'min'
      },
      combine: {
        files: {
          'css/app.min.css': [
            'simplesite/css/vendor/normalize.css',
            'simplesite/css/vendor/skeleton.css',
            'simplesite/css/vendor/main.css',
            'simplesite/css/vendor/embetter.css',
            'css/app/app.css'
          ]
        }
      }
    },

    postcss: {
      options: {
        map: false, // inline sourcemaps
        processors: [
          require('autoprefixer')({browsers: 'last 2 versions'}), // add vendor prefixes
        ]
      },
      dist: {
        src: 'css/app.min.css'
      }
    },

    clean: ['js/min']

  });

  // Load plugins
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  grunt.loadNpmTasks('grunt-postcss');
  grunt.loadNpmTasks('grunt-babel');
  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-contrib-copy');


  // Default task(s).
  // 'copy',
  grunt.registerTask('default', ['babel', 'uglify', 'cssmin', 'postcss:dist', 'clean']);

};
