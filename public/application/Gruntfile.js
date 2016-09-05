module.exports = function (grunt) {
    require('time-grunt')(grunt);
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        copy: {
            dev: {
                files: [
                    {
                        expand: true,
                        cwd: 'assets/resources/js/vendor/',
                        src: '**',
                        dest: 'assets/dev/js/vendor/'
                    },
                    {
                        expand: true,
                        cwd: 'assets/resources/copies/',
                        src: '**',
                        dest: 'assets/dev/'
                    },
                    {
                        expand: true,
                        cwd: 'assets/resources/fonts/',
                        src: '**',
                        dest: 'assets/dev/fonts/'
                    }
                ]
            },
            dist: {
                files: [
                    {
                        expand: true,
                        cwd: 'assets/resources/js/vendor/',
                        src: '**',
                        dest: 'assets/dist/js/vendor/'
                    },
                    {
                        expand: true,
                        cwd: 'assets/resources/copies/',
                        src: '**',
                        dest: 'assets/dist/'
                    },
                    {
                        expand: true,
                        cwd: 'assets/resources/fonts/',
                        src: '**',
                        dest: 'assets/dist/fonts/'
                    }
                ]
            }
        },

        compass: {
            dev: {
                options: {
                    config: 'compass-dev.rb',
                    environment: 'development',
                    sourcemap: true
                }
            },
            dist: {
                options: {
                    config: 'compass-dist.rb',
                    environment: 'production',
                    sourcemap: true,
                    force: true
                }
            }
        },//sass

        watch: {
            configs: {
                files: [
                    'assets/resources/copies/**/*.*',
                    'assets/resources/fonts/**/*',
                    'assets/resources/js/vender/**/*'
                ],
                options: {
                    livereload: false
                },
                tasks: ['copy:dev']
            },
            css: {
                files: [
                    'assets/resources/sass/**/*.sass',
                    'assets/resources/sass/**/*.scss'
                ],
                options: {
                    livereload: false
                },
                tasks: ['compass:dev']
            },
            js: {
                files: [
                    'assets/resources/js/**/*.js'
                ],
                options: {
                    livereload: false
                },
                tasks: ['uglify:dev']
            },
            grunt: {
                files: ['Gruntfile.js']
            },
        }
    });

    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-htmlmin');
    grunt.loadNpmTasks('grunt-contrib-imagemin');
    grunt.loadNpmTasks('grunt-contrib-compass');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-copy');

    grunt.registerTask('default', ['copy:dev', 'compass:dev']);
    grunt.registerTask('w', ['watch']);
    grunt.registerTask('deploy', ['copy:dist', 'compass:dist']);
};
