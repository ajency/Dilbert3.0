module.exports = function(grunt) {
  require('jit-grunt')(grunt);

  grunt.initConfig({
    less: {
      development: {
        options: {
          compress: false,
          yuicompress: false,
          optimization: 2
        },
        files: {
          "../css/styles.css": "less/styles.less" // destination file and source file
          // "../../../public/views/assets/css/styles.css": "less/styles.less",
          // "../../../public/assets/css/styles.css": "less/styles.less"
        }
      }
    },
    watch: {
      styles: {
        files: ['less/**/*.less'], // which files to watch
        tasks: ['less'],
        options: {
          nospawn: true
        } 
      }
    }
  });

  grunt.registerTask('default', ['less', 'watch']);
};