## Configurationless js and css compiler for Symphony CMS

### Installation

From your Symphony source directory, clone the repository to

```
git submodule add https://github.com/iwyg/symphony_assetscompiler.git extensions/assets --recursive
```
Install and enanble the extensions in `System > Extensions`


### Usage

Set a `data-compile="true"` attribute on script and link elements that should be concatenated an minified, e.g:

```html
<script src="{$workspace}/scripts/vendor.js" data-compile="true"/>
<script src="{$workspace}/scripts/main.js" data-compile="true"/>
```

Output:

```html
<script src="path/to/workspace/dist/application-35c474e5fda53358dea0255b3f74b843.js"/>
```
