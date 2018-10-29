var gulp = require('gulp');
var fs = require('fs');
var GulpSSH = require('gulp-ssh')

var dev_config = JSON.parse(fs.readFileSync('./dev-config.json'));


var config = {
  host: dev_config.config_ssh.host,
  port: dev_config.config_ssh.port,
  username: dev_config.config_ssh.username,
  //password: 'navadra'
  privateKey: fs.readFileSync( dev_config.config_ssh.privateKey )
}
 
var gulpSSH = new GulpSSH({
  ignoreErrors: false,
  sshConfig: config
})
 
// ******************************
// ***** TEST if SSH works : Writes inside "result.log" the result of the test
gulp.task('test', function () {
  return gulpSSH
    .exec(['ls -la --color'], {filePath: 'commands.log'})
    .pipe(gulp.dest('./'))
})
 
// ******************************
// ***** Put the correct rights inside game folder
gulp.task('rights', function () {
  return gulpSSH
    .exec(['sudo chown -R www-data:www-data /var/www/jeu', 'sudo chmod -R 777 /var/www/jeu'], {filePath: 'commands.log'})
    .pipe(gulp.dest('./'))
})
 
// ******************************
// ***** Copies the app folder
gulp.task('app', function () {
  return gulp
    .src(['./app/**/*.*'])
    .pipe(gulpSSH.dest('/var/www/jeu/app/'))
})

// ******************************
// ***** Copies the vendro folder
gulp.task('webroot', function () {
  return gulp
    .src(['./webroot/**/*.*'])
    .pipe(gulpSSH.dest('/var/www/jeu/webroot/'))
})

// ******************************
// ***** Copies almost all folders (not node_modules)
gulp.task('all', function () {
  return gulp
    .src(['./**/*.*', '!./dev-config.json_base', '!./gulpfile.js', '!./logs/**', '!./package.json', '!./readme.md', '!./dev-config.json', '!./*.log', '!./.git/**', '!./.idea/**', '!**/node_modules/**'])
    .pipe(gulpSSH.dest('/var/www/jeu/'))
})


