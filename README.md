Simple PHP filesystem API for web filebrowsers
==


**WARNING** : You should place theses files inside a secure folder (eg: .htacces)

Configure `$BASE_PATH` in `config.php` which acts as a jail and root for all the commands.

commands :

   * GET
       * `cmd=view&file=path/to/file.jpg`  : display/download arbitraty file

   * POST
       * `cmd=get&path=a/nother/path` : directory listing
       * `cmd=newdir&dir=a/nother/path` : creates a directory
       * `cmd=rename&oldname=path/to/old.txt&newname=path/to/new.jpg` : renames a file or directory
       * `cmd=delete&file=a/nother/file.jpeg` : deletes a file or directory

  * UPLOAD
      * cmd=upload (classic form upload, swfupload...) : with multipart/form-encoded FILES
      * cmd=upload (html5 drag'n'drop) : with X_FILE_NAME header with raw contents in the request body


Can be used with [Ext.ux.filebrowser][1]


  [1]: https://github.com/revolunet/Ext.ux.filebrowser