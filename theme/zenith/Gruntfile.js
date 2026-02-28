/* eslint-env node */

/**
 * Zenith theme Grunt configuration.
 *
 * @package theme_zenith
 */
module.exports = function(grunt) {
    'use strict';

    const path = require('path');

    grunt.initConfig({
        // Minify AMD modules.
        uglify: {
            amd: {
                files: [{
                    expand: true,
                    cwd: 'amd/src',
                    src: ['**/*.js'],
                    dest: 'amd/build',
                    ext: '.min.js',
                }],
                options: {
                    sourceMap: true,
                    sourceMapIncludeSources: true,
                },
            },
        },

        // ESLint for JS files.
        eslint: {
            amd: {
                src: ['amd/src/**/*.js'],
            },
        },

        // Watch for file changes.
        watch: {
            amd: {
                files: ['amd/src/**/*.js'],
                tasks: ['uglify:amd'],
            },
        },
    });

    // Load plugins.
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-eslint');

    // Register tasks.
    grunt.registerTask('amd', ['eslint:amd', 'uglify:amd']);
    grunt.registerTask('lint', ['eslint:amd']);
    grunt.registerTask('default', ['amd']);
};
