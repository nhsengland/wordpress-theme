module.exports = function (grunt) {
  'use strict'

  grunt.loadNpmTasks('grunt-contrib-watch')
  grunt.loadNpmTasks('grunt-sass')
  grunt.loadNpmTasks('grunt-contrib-imagemin')
  grunt.loadNpmTasks('grunt-standard')
  grunt.loadNpmTasks('grunt-modernizr')
  grunt.loadNpmTasks('grunt-browserify')
  grunt.loadNpmTasks('grunt-exorcise')
  grunt.loadNpmTasks('grunt-contrib-copy')
  grunt.loadNpmTasks('grunt-contrib-clean')
  grunt.loadNpmTasks('grunt-contrib-uglify')
  grunt.loadNpmTasks('grunt-svgmin')

  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

    clean: {
      production: {
        src: [
          'static/'
        ]
      }
    },

    sass: {
      options: {
        outputStyle: 'compressed',
        sourceMap: true,
        sourceComments: true
      },
      production: {
        files: {
          'static/main.min.css': 'assets/scss/main.scss'
        }
      }
    },

    modernizr: {
      production: {
        'crawl': false,
        'customTests': [],
        'dest': 'static/lib/modernizr.min.js',
        'tests': [
          'flexbox',
          'svg',
          'inputtypes'
        ],
        'options': [
          'html5printshiv',
          'html5shiv',
          'setClasses',
          'mq'
        ],
        'uglify': true
      }
    },

    browserify: {
      options: {
        browserifyOptions: {
          debug: true
        }
      },
      production: {
        files: {
          'static/main.min.js': 'assets/js/main.js'
        }
      }
    },

    exorcise: {
      production: {
        files: {
          'static/main.min.js.map': 'static/main.min.js'
        }
      }
    },

    uglify: {
      dist: {
        files: {
          'static/main.min.js': 'static/main.min.js'
        }
      }
    },

    copy: {
      production: {
        files: [
          {expand: false, src: ['node_modules/jquery/dist/jquery.min.js'], dest: 'static/lib/jquery.min.js'},
          {expand: false, src: ['node_modules/jquery-ui/ui/minified/jquery.ui.datepicker.min.js'], dest: 'static/lib/jquery-ui/jquery.datepicker.min.js'},
          {expand: true, cwd: 'node_modules/jquery-ui/themes/smoothness/', src: ['**'], dest: 'static/lib/jquery-ui/themes/smoothness/'},
          {expand: true, cwd: 'assets/fonts/', src: ['**'], dest: 'static/fonts/'}
        ]
      }
    },

    imagemin: {
      options: {
        cache: false
      },

      dist: {
        files: [{
          expand: true,
          cwd: 'assets/img',
          src: ['**/*.{png,jpg,gif,ico}'],
          dest: 'static/img'
        }]
      }
    },

    svgmin: {
      options: {},
      dist: {
        files: [
          {
            expand: true,
            cwd: 'assets/img/',
            src: ['*.svg', 'social-media-icons/*.svg'],
            dest: 'static/img/'
          }
        ]
      }
    },

    _watch: {
      less: {
        files: ['assets/scss/*.scss', 'assets/scss/*/*.scss'],
        tasks: ['sass']
      },
      js: {
        files: ['assets/js/*.js', 'assets/js/*/*.js'],
        tasks: ['standard', 'browserify', 'exorcise']
      }
    },

    standard: {
      production: {
        src: [
          'Gruntfile.js',
          'assets/js/main.js'
        ]
      }
    }
  })

  // Hack to make `img` task work
  grunt.registerTask('img-mkdir', 'mkdir static/img', function () {
    var fs = require('fs')

    fs.mkdirSync('static')
    fs.mkdirSync('static/img')
  })

  grunt.renameTask('watch', '_watch')

  grunt.registerTask('watch', [
    'default',
    '_watch'
  ])

  grunt.registerTask('default', [
    'clean',
    'img-mkdir',
    'imagemin',
    'sass',
    'svgmin',
    'standard',
    'copy',
    'browserify',
    'exorcise',
    'uglify',
    'modernizr'
  ])
}
