module.exports = function(grunt) {

  grunt.initConfig({
    //pkg: grunt.file.readJSON('package.json'),
    scss: {
      dist: {
        files: {			
          'css/main.css' : 'scss/main.scss'
	}
      },
    },
    watch: {
      css: {
      // We watch and compile sass files as normal but don't live reload here
        files: ['**/*.scss'],
        tasks: ['scss'],
      },
      livereload: {
      // Here we watch the files the sass task will compile to
      // These files are sent to the live reload server after sass compiles to them
        options: { livereload: true },
        files: ['css/**/*'],
      }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-scss');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.registerTask('default',['watch']);
}
