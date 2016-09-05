module.exports = function (grunt) {
	require('time-grunt')(grunt);

	var templateDir = './template/metronic_v4.5.5/theme',
	jsFooterFiles = [
		/* BEGIN CORE PLUGINS */
		templateDir + '/assets/global/plugins/bootstrap/js/bootstrap.min.js',
		templateDir + '/assets/global/plugins/js.cookie.min.js',
		templateDir + '/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js',
		templateDir + '/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js',
		templateDir + '/assets/global/plugins/jquery.blockui.min.js',
		templateDir + '/assets/global/plugins/uniform/jquery.uniform.min.js',
		templateDir + '/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js',
		/* END CORE PLUGINS 
		BEGIN PLUGINS */
		templateDir + '/assets/global/plugins/jquery-ui/jquery-ui.min.js',
		templateDir + '/assets/global/plugins/moment.min.js',
		templateDir + '/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js',
		templateDir + '/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js',
		templateDir + '/assets/global/plugins/bootstrap-datepicker/js/locales/bootstrap-datetimepicker.pt-BR.js',
		templateDir + '/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js',
		templateDir + '/assets/global/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js',
		templateDir + '/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js',
		templateDir + '/assets/global/plugins/typeahead/handlebars.min.js',
		templateDir + '/assets/global/plugins/typeahead/typeahead.bundle.min.js',
		templateDir + '/assets/global/plugins/bootbox/bootbox.min.js',
		templateDir + '/assets/global/plugins/icheck/icheck.min.js',
		templateDir + '/assets/global/plugins/jquery-validation/js/jquery.validate.min.js',
		templateDir + '/assets/global/plugins/jquery-validation/js/additional-methods.min.js',
		templateDir + '/assets/global/plugins/jquery-nestable/jquery.nestable.js',
		templateDir + '/assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js',
		templateDir + '/assets/global/plugins/jquery-multi-select/js/jquery.multi-select.js',
		templateDir + '/assets/global/plugins/select2/js/select2.full.min.js',
		/* END PLUGINS
		BEGIN THEME GLOBAL SCRIPTS */
		templateDir + '/assets/global/scripts/app.min.js',
		/* BEGIN THEME LAYOUT SCRIPTS */
		templateDir + '/assets/layouts/layout/scripts/layout.min.js',
		templateDir + '/assets/layouts/layout/scripts/demo.min.js',
		templateDir + '/assets/layouts/global/scripts/quick-sidebar.min.js'
		/* END THEME LAYOUT SCRIPTS */
	],
	globalCss = [
		templateDir + '/assets/global/plugins/font-awesome/css/font-awesome.min.css',
		templateDir + '/assets/global/plugins/simple-line-icons/simple-line-icons.min.css',
		templateDir + '/assets/global/plugins/bootstrap/css/bootstrap.min.css',
		templateDir + '/assets/global/plugins/uniform/css/uniform.default.css',
		templateDir + '/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css',
		templateDir + '/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css',
		templateDir + '/assets/pages/css/error.min.css',
		/* BEGIN PLUGINS */
		templateDir + '/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css',
		templateDir + '/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css',
		templateDir + '/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css',
		templateDir + '/assets/global/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css',
		templateDir + '/assets/global/plugins/typeahead/typeahead.css',
		templateDir + '/assets/global/plugins/icheck/skins/all.css',
		templateDir + '/assets/global/plugins/bootstrap-select/css/bootstrap-select.min.css',
		templateDir + '/assets/global/plugins/jquery-multi-select/css/multi-select.css',
		templateDir + '/assets/global/plugins/select2/css/select2.min.css',
		templateDir + '/assets/global/plugins/select2/css/select2-bootstrap.min.css'
	];
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),

		uglify: {
			dist: {
				options: {
					banner: "/*! <%= pkg.name %> - <%= pkg.version %> - By Ricardo Viana */",
					mangle: true,
                    compress: false,
                    wrap: false,
                    exportAll: false,
                    beautify: false
				},
				files: {
					'dist/js/footer.js': jsFooterFiles
				}
			},
			dev: {
				options: {
                    preserveComments: false,
                    mangle: false,
                    compress: false,
                    wrap: false,
                    exportAll: false,
                    beautify: {
                        width: 80,
                        beautify: true
                    }
                },
                files: {
                	'dev/js/footer.js': jsFooterFiles
                }
			},
			maindev: {
				options: {
                    preserveComments: false,
                    mangle: false,
                    compress: false,
                    wrap: false,
                    exportAll: false,
                    beautify: {
                        width: 80,
                        beautify: true
                    }
                },
                files: {
                	'./dev/js/custom.js': ['./resources/js/*.js'],
                }
			},
			maindist: {
				options: {
					banner: "/*! <%= pkg.name %> - <%= pkg.version %> - By Ricardo Viana */",
					mangle: true,
                    compress: false,
                    wrap: false,
                    exportAll: false,
                    beautify: false
				},
				files: {
                	'./dev/js/custom.js': ['./resources/js/*.js'],
                }
			},
		},
		cssmin: {
			dist: {
				options: {
					 banner: '/*! MyLib.js 1.0.0 | Ricardo Viana (@ricardoviana) | MIT Licensed */',
					 keepSpecialComments: 0

				},
				files: {
					'dist/css/global.css': globalCss
				}
			},
			dev: {
				options: {
					 banner: '/*! MyLib.js 1.0.0 | Ricardo Viana (@ricardoviana) | MIT Licensed */',
					 keepSpecialComments: 0
				},
				files: {
					'dev/css/global.css': globalCss
				}
			}
		},
		copy: {
			dev: {
				files: [
					{
						expand: true,
						cwd: templateDir + '/assets/global/plugins/font-awesome/fonts/',
						src: '**',
						dest: 'dev/fonts'
					},
					{
						expand: true,
						cwd: templateDir + '/assets/global/plugins/simple-line-icons/fonts/',
						src: '**',
						dest: 'dev/css/fonts'
					},
					{
						expand: true,
						cwd: templateDir + '/assets/global/plugins/icheck/skins/',
						src: '**',
						dest: 'dev/css/icheck/skins/'
					},
					{
                        expand: true,
                        cwd: './resources/js/vendor/',
                        src: '**',
                        dest: './dev/js/vendor/'
                    },
                    {
                        expand: true,
                        cwd: './resources/js/pages/',
                        src: '**',
                        dest: './dev/js/pages/'
                    },
                    {
                    	expand: true,
                    	cwd: templateDir + '/assets/global/plugins/jquery-multi-select/img/',
                    	src: '*',
                    	dest: 'dev/img/'
                    },
                    {
                    	expand: true,
                    	cwd: templateDir + '/assets/global/plugins/fancybox/source/',
                    	src: ['*.png', '*.gif'],
                    	dest: 'dev/css/'
                    }
				]
			},
			dist: {
				files: [
					{
						expand: true,
						cwd: templateDir + '/assets/global/plugins/font-awesome/fonts/',
						src: '**',
						dest: 'dist/fonts'
					},
					{
						expand: true,
						cwd: templateDir + '/assets/global/plugins/simple-line-icons/fonts/',
						src: '**',
						dest: 'dist/css/fonts'
					},
					{
						expand: true,
						cwd: templateDir + '/assets/global/plugins/icheck/skins/',
						src: '**',
						dest: 'dist/css/icheck/skins/'
					},
					{
                        expand: true,
                        cwd: './resources/js/vendor/',
                        src: '**',
                        dest: './dist/js/vendor/'
                    },
                    {
                        expand: true,
                        cwd: './resources/js/pages/',
                        src: '**',
                        dest: './dist/js/pages/'
                    },
                    {
                    	expand: true,
                    	cwd: templateDir + '/assets/global/plugins/jquery-multi-select/img/',
                    	src: '*',
                    	dest: 'dist/img/'
                    }
				]
			}
		},
		compass: {
			dev: {
				options: {
					config: 'compass-dev.rb',
					environment: 'dev',
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
		},
		imagemin: {
			dev: {
				files: [{
					expand: true,
					cwd: 'resources/img/',
					src: ['**/*.{png,jpg,gif}'],
					dest: 'dev/img/'
				}]
			},
			dist: {
				files: [{
					expand: true,
					cwd: 'resources/img/',
					src: ['**/*.{png,jpg,gif}'],
					dest: 'dist/img/'
				}]
			}
		},
		watch: {
			css: {
				files: [
					'./resources/sass/**/*.sass',
					'./resources/sass/**/*.scss'
				],
				options: {
					livereload: false
				},
				tasks: ['compass:dev']
			},
			js: {
				files: [
					'./resources/js/**/*.js'
				],
				options: {
					livereload: false
				},
				tasks: ['uglify:maindev']
			},
			grunt: {
				files: ['Gruntfile.js']
			},
			images: {
				files: [
					'resources/img/**/*'
				],
				options: {
					livereload: false
				},
				tasks: ['imagemin:dev', 'imagemin:dist']
			}
		}
	});

	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-cssmin');
	grunt.loadNpmTasks('grunt-contrib-htmlmin');
	grunt.loadNpmTasks('grunt-contrib-imagemin');
	grunt.loadNpmTasks('grunt-contrib-compass');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-copy');

	grunt.registerTask('default', ['copy:dev', 'uglify:dev', 'uglify:maindev', 'cssmin:dev', 'imagemin:dev', 'compass:dev']);

	grunt.registerTask('w', ['watch']);

	grunt.registerTask('deploy', ['copy:dist', 'uglify:dist', 'uglify:maindist', 'cssmin:dist', 'imagemin:dist', 'compass:dist']);
};