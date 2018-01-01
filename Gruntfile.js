module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
   
    
    concat: {
      
      basic_and_extras: {
        files: {         
          'dist/js/app.js': [
              'js/components/test-comp-2.js',             
              'js/components/test-comp-1.js',             
              'js/components/test-comp-3.js',             
              'js/plugin-app.js'
            ], 
        },
      },
    },
    
    uglify: {
      options: {
        banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n'
      },
      build: {
        src: [
          'dist/js/*.js',
          '!dist/js/app.min.js'
        ],
        dest: 'dist/js/app.min.js'
      }
    },
    
     sass: { // sass tasks
      dist: {
        options: {
         // compass: true, // enable the combass lib, more on this later
          style: 'expanded' // we don't want to compress it
        },
        files: {
          'css/app.css': 'sass/*.scss', // this is our main scss file
          
        }
      }
    },

    cssmin: { // minifying css task
      dist: {
        files: {
          'dist/css/app.min.css': 'css/*.css'//
         // 'dist/css/header-generic.min.css': 'dist/css/header-generic.css'
        }
      }
    },

    watch: { // watch task for general work
      sass: {
        files: ['sass/**/*.scss'],
        tasks: ['sass:dist']
      },
      styles: {
        files: ['css/app.css'],
        tasks: ['cssmin']
      }
    },
        browserSync: {
            dev: {
                bsFiles: {
                    src : [
                        'css/*.css',
                        'class/*.php',
                        'js/*.js'
                    ]
                },
                options: {
                    watchTask: true,
                    proxy: 'http://localhost/gaadcalc/'
                }
            }
        }
    
   
  });

  // Load the plugin that provides the "uglify" task.
  grunt.loadNpmTasks('grunt-browser-sync');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-concat');  
  grunt.loadNpmTasks('grunt-contrib-sass');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  grunt.loadNpmTasks('grunt-contrib-watch');  
  //grunt.loadNpmTasks('grunt-contrib-compress');
  // Default task(s).
  grunt.registerTask('default', [ 'sass', 'concat', 'uglify', /**/'cssmin' ]);
  grunt.registerTask('dev', [ 'browserSync', 'watch', 'sass' ]);

};