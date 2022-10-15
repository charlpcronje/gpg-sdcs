# Project Outline

- HTML form gets submitted to server
- All `https` requests gets routed to `index.php` by using rewrite rules in `.htaccess`
- There are 3 ways how I can know what data is expected when I receive an HTTP Request or POST or Whatever
  - There is a input with the name of manifest ie: `<input name="manifest" value="{JSON DATA}"/>`
  - The Form is submitted to a route that has a manifest.json again with `JSON DATA`
  - I receive no Manifest from in input or a `manifest.json`, defaults are used.
- Once I've parsed the JSON DATA I can validate each input as I iterate through them.
- All data will be written to files and saved in a folder corresponding to client medical ID
- Once all the files are written and all the uploaded files are moved to the folder it is encrypted with GPG.

