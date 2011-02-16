PHP filesystem API
==

Can be used with [Ext.ux.filebrowser][1]

**WARNING** : You should place theses files inside a secure folder (eg: .htacces). Also set $BASE_PATH outside your Apache DOC_ROOT.

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
      * `cmd=upload` with multipart/form-encoded FILES  (classic form upload, swfupload...) 
      * `cmd=upload` with X_FILE_NAME header with raw contents in the request body  (html5 drag'n'drop) 





  [1]: https://github.com/revolunet/Ext.ux.filebrowser