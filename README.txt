INSTALL

1. Git-Directory
----------------
You need a directory where all git repositories will be created. E.g.
/var/www/git
After enabling the Module set this in the administration interface

2. Apache Config for write support
----------------------------------
Enable DAV support:
> a2enmod dav dav_fs

Add the following lines to the apache virtualhost directive:
  <Directory /var/www/git/>
    Dav On
  </Directory>

3. Apache Access control to git directories
-------------------------------------------
Enable the "external authentication" module:
> apt-get install libapache2-mod-authnz-external

Add the following lines to the apache virtualhost directive:
  DefineExternalAuth git_repo pipe DRUPAL_ROOT/modules/git_repo/git_auth
  DefineExternalGroup git_repo pipe DRUPAL_ROOT/modules/git_repo/git_group

Replace DRUPAL_ROOT to the correct path to your drupal installation, e.g.
/var/www

4. Use memcached to speed up authentication
-------------------------------------------
The external scripts will be called over and over. Loading the drupal
libraries every time to check for the user takes a lot of time. If you
install memcached the authorization will be saved for some seconds,
speeding up things.

> apt-get install memcached php5-memcache
> /etc/init.d/apache2 reload
