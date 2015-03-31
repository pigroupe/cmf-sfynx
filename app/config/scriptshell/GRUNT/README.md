# Atelier Optimisation front avec Grunt

## Installation

Vous devez disposer de nodejs et de npm.

    ./install.sh

## Explications

Un certain nombre de plugins Grunt sont configurés directement depuis `yo-bootstrap`:

+ concat
+ concurrent
+ copy
+ connect
+ coffee
+ cssmin
+ htmlmin
+ imagemin
+ jshint
+ less
+ mocha
+ rev
+ svg-min
+ usemin

Sinon, voici une liste de plugins pratique. Pour les rendre disponibles dans votre `Gruntfile`, pensez à ajouter :

    grunt.loadNpmTasks('grunt-browser-sync');
    grunt.loadNpmTasks('grunt-uncss');
    grunt.loadNpmTasks('grunt-yslow');
    grunt.loadNpmTasks('grunt-shell');
    grunt.loadNpmTasks("grunt-remove-logging");
    grunt.loadNpmTasks('grunt-spritesmith');
    grunt.loadNpmTasks('grunt-newer');
    grunt.loadNpmTasks('grunt-notify');
    grunt.loadNpmTasks('grunt-lazyload');

## Ca change la vie du dev
 
Synchronisation des navigateurs

    browserSync: {
          default_options: {
              bsFiles: {
                  src: [
                      "app/styles/*.css",
                      "app/*.html"
                  ]
              },
              options: {
                  watchTask: true,
                  server: './app'
              }
          }
      },
 
## Performance
  
Suppression du css inutilisé  

    uncss: {
      dist: {
          files: {
              'dist/styles/main.css': [
                  'app/index.html'
              ]
          },
          options: {
              compress:true
          },
          ignore: [
          ]
      }
    },  
  
 
  
Utilisation d'un CDN

    cdnify: {
      dist: {
        html: ['<%= yeoman.dist %>/*.html']
      }
    },

## Tests de perf

Test yslow

    yslow: {
      options: {
          thresholds: {
              weight: 180,
              speed: 1000,
              score: 80,
              requests: 15
          },
          yslowOptions: {
              viewport: "1920x1080"
          }
      },
      pages: {
          files: [
              {
                  src: 'http://aw-atelier/'
              }
          ]
      }
    },

Test PageSpeed 

> une clef est nécessaire au bout d'un moment    

    pagespeed: {
        options: {
            nokey: true,
            url: "https://developers.google.com"
        },
        prod: {
            options: {
                url: "http://formation.alterway.fr/",
                locale: "fr_FR",
                strategy: "desktop",
                threshold: 80
            }
        }
    },

## Images

### Sprites

    sprite:{
      myName: {
          src: 'source/images/mySprites/*.png',
          destImg: 'app/images/sprites/file1.png',
          destCSS: 'app/styles/sprites/file1.css',
    
      }
    }


