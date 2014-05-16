# Description

This Gallery plugin for Croogo can create albums and upload photos

## Instalation

- Clone/download [Imagine](http://github.com/CakeDC/Imagine) plugin into
  `APP/Plugin/Imagine` and activate it manually, ie:

  `Console/cake ext activate plugin Imagine -f`

- Clone/download [Assets](http://github.com/xintesa/Assets) plugin into
  `APP/Plugin/Assets` and activate it in the admin panel or manually:

  `Console/cake ext activate plugin Asset`

- Clone/download [Gallery](http://github.com/rchavik/Assets) plugin into
  `APP/Plugin/Assets` and activate it in the admin paneli or imanually:

  `Console/cake ext activate plugin Gallery`

Create album and upload photos, you can access the albums in
http://yoursitewithcroogo/gallery, or include it in any block or node record
by using the shortcode `[Gallery: gallery-slug]`.

The plugin will automatically substitute it with your photo album.

Eg.:

`[Gallery:my_carnival_brazil_album]`

## Dependencies and Compatibility

- Croogo > 2.0.0
- [Imagine](http://github.com/CakeDC/Imagine) plugin
- [Assets](http://github.com/xintesa/Assets) plugin

Original Author: Edinei L. Cipriani
E-mail: <phpedinei@gmail.com>
Website: http://www.edineicipriani.com.br
