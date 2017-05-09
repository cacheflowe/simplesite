module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

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
              "js/three/three.min.js",
              "js/vendor/fetch.js",
              "js/vendor/share-out.js",
              "js/vendor/diddrag.js",
              "js/vendor/big-picture.min.js",
              "js/haxademic.js/math-util.js",
              "js/haxademic.js/easing-float.js",
              "js/haxademic.js/linear-float.js",
              "js/haxademic.js/linear-float-to.js",
              "js/haxademic.js/penner.js",
              "js/haxademic.js/float-buffer.js",
              "js/haxademic.js/pointer-pos.js",
              "js/haxademic.js/mobile-util.js",
              "js/haxademic.js/keyboard-util.js",
              "js/haxademic.js/dom-util.js",
              "js/min/app/three-scene.js",
              "js/min/app/image.js",
              "js/min/app/pointmaker.js",
              "js/min/app/word-mesh.js",
              "js/min/app/camera.js",
              "js/min/app/mouse3d.js",
              "js/min/app/footer.js",
              "js/min/app/contact-bar.js",
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
            'css/normalize.css',
            'css/app.css'
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
  grunt.registerTask('default', ['copy', 'babel', 'uglify', 'cssmin', 'postcss:dist', 'clean']);

};
