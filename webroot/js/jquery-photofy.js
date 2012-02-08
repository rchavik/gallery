/*-----------------------------------------------------------------------
Copyright (c) 2011 Tremayne Christ, http://tremaynechrist.co.uk/

MIT LICENSE

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
"Software"), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
-----------------------------------------------------------------------*/

(function ($) {

    var photofy_interval;

    var methods = {
        init: function (options) {
            var defaults = {
                delay: 8000,
                fadeDuration: 300,
                changeTimeout: 30,
                imageSource: null,
                maxImages: 12,
                highlight: true,
                containerPosition: "relative",
                overlayBackColor: "white",
                overlayForeColor: "#424547",
                overlayTransparency: 0.8,
                shuffleAtStart: false,
                shuffle: true,
                previewPause: false,
                copyright: true,
                copyrightText: "&copy; Copyright " + new Date().getFullYear(),
                ready: function () {
                    //
                },
                update: function () {
                    //
                },
                select: function (obj, ui) {
                    obj.append('<div class="photofy_overlay" style="color:' + options.overlayForeColor + ';display:none;position:absolute;top:0;right:0;bottom:0;left:0"><div class="photofy_overlay_background" style="position:absolute;top:0;right:0;bottom:0;left:0;background:' + options.overlayBackColor + ';opacity:' + options.overlayTransparency + ';filter:alpha(opacity=' + options.overlayTransparency * 100 + ')"></div><div class="photofy_overlayContent" style="display:none;position:absolute;top:20px;left:20px;bottom:20px;right:20px"><div class="photofy_overlay_background" style="position:absolute;top:0;right:0;bottom:0;left:0;background:' + options.overlayBackColor + ';opacity:' + options.overlayTransparency + ';filter:alpha(opacity=' + options.overlayTransparency * 100 + ')"></div><div class="photofy_overlay_html" style="position:absolute;top:0;left:0;bottom:0;right:60%;padding:20px;display:none"></div><a href="#" class="photofy_overlay_close" style="color:' + options.overlayForeColor + ';position:absolute;bottom:20px;left:20px;height:auto;width:auto;text-decoration:underline">Close</a><div class="photofy_overlayImage" style="position:absolute;top:0;right:0;bottom:0;left:40%;border:5px solid transparent;overflow:hidden"></div></div></div>')
                    obj.find("a.photofy_overlay_close").click(function () {
                        obj.find(".photofy_overlay").fadeOut(function () {
                            $(this).remove();
                        });
                        return false;
                    });
                    var photofy_overlay_html_wrapper = obj.find(".photofy_overlay_html");
                    photofy_overlay_html_wrapper.append(ui.find(".photofy_html").html());
                    var photofy_overlay_image_wrapper = obj.find(".photofy_overlayImage");
                    photofy_overlay_image_wrapper.append('<img src="' + ui.attr("href") + '" style="position:relative;float:right;width:auto;height:100%;display:none" />');
                    if (options.copyright) {
                        photofy_overlay_image_wrapper.append('<div style="position:absolute;top:0;right:0;bottom:0;left:0"><div style="position:absolute;right:0;bottom:0;left:0;height:21px;text-align:right;padding:5px;height:12px;line-height:12px;color:' + options.overlayForeColor + ';font-size:11px"><div class="photofy_copyright_background" style="position:absolute;top:0;right:0;bottom:0;left:0;background:' + options.overlayBackColor + ';opacity:' + options.overlayTransparency / 1.2 + ';filter:alpha(opacity=' + options.overlayTransparency * 100 + ')"></div><span style="position:relative">' + options.copyrightText + '</span></div></div>');
                    }
                    var photofy_overlay_image = obj.find(".photofy_overlayImage img");
                    if (options.highlight) {
                        obj.find(".photofy_thumbnail").stop(true).fadeTo(1000, 1);
                    }
                    obj.find(".photofy_overlay").fadeIn(200, function () {
                        obj.find(".photofy_overlayContent").fadeIn(function () {
                            if (photofy_overlay_image.width() > photofy_overlay_image_wrapper.width()) {
                                photofy_overlay_image.css({ left: -(photofy_overlay_image_wrapper.width() - photofy_overlay_image.width()) / 2 });
                            }
                            photofy_overlay_html_wrapper.fadeIn();
                            photofy_overlay_image.fadeIn();
                        });
                    });
                    return false;
                }
            };
            options = $.extend(defaults, options);

            return this.each(function () {

                var photofy_imageList = [];         
                var photofy_imageCache = [];        
                var photofy_changeOrderList = [];   
                var photofy_imageCount = 0;         
                var photofy_imageLoadCount = 0;     
                var photofy_currentImageIndex = 0;  
                var photofy_highlightPause = false; 
                var imagesAppended = false;         
                var photofyImageFullyLoadedCount = 0;

                var obj = $(this);

                obj.css("position", options.containerPosition);

                if (options.imageSource) {
                    if ($.isArray(options.imageSource)) {
                        photofySetup(options.imageSource);
                    }
                    else {
                        $.ajax({
                            url: options.imageSource,
                            type: "GET",
                            success: function (data) {
                                photofySetup(eval(data));
                            },
                            error: function () {
                                alert("Error getting image list from '" + options.imageSource + "'");
                            }
                        });
                    }
                }

                function photofySetup(imageArray) {
                    photofy_imageCount = imageArray.length;
                    if (options.shuffleAtStart) {
                        imageArray = imageArray.sort(function () { return 0.5 - Math.random() });
                    }
                    for (var i = photofy_imageLoadCount; i < photofy_imageCount && i < options.maxImages; i++) {
                        var photofy_currentImage = imageArray[i];
                        var photofy_image = new Image();
                        $(photofy_image).load(function () {
                            photofyCheckReady();
                        });
                        photofy_image.alt = "Photofy Image";
                        photofy_image.src = photofy_currentImage.ImageUrl;
                        photofy_imageCache.push(photofy_image);
                        photofy_imageList.push(photofy_currentImage);
                        photofy_changeOrderList.push(i);
                        obj.append('<a class="photofy_thumbnail" photofy="' + i + '" href="' + photofy_currentImage.LinkUrl + '"></a>');
                        var photofy_link = obj.find(".photofy_thumbnail").last();
                        photofy_link.append(photofy_image);
                        photofy_link.append('<span class="photofy_html" style="display:none">' + photofy_currentImage.HTML + '</span>');
                        photofy_link.click(function () {
                            photofy_highlightPause = false;
                            return options.select(obj, $(this));
                        });
                        if (options.highlight) {
                            photofy_link.mouseenter(function () {
                                var fadeClass = "canFade";
                                var thumbnails = obj.find(".photofy_thumbnail");
                                thumbnails.addClass(fadeClass);
                                $(this).removeClass(fadeClass);
                                $("a.canFade").fadeTo(100, 0.3);
                                thumbnails.removeClass(fadeClass);
                                $(this).stop(true).fadeTo(80, 1);
                                photofy_highlightPause = true;
                            });
                        }
                        photofy_imageLoadCount++;
                        photofy_currentImageIndex++;
                    }
                    obj.mouseleave(function () {
                        obj.find(".photofy_thumbnail").stop(true).fadeTo(300, 1);
                        photofy_highlightPause = false;
                    });
                    processImageCaching();
                    var cacheTicker = setInterval(processImageCaching, options.delay);
                    function processImageCaching() {
                        if (photofy_imageLoadCount < photofy_imageCount) {
                            for (var i = 0; i < options.maxImages && photofy_imageLoadCount < photofy_imageCount; i++) {
                                var o = i + photofy_imageLoadCount;
                                var photofy_currentImage = imageArray[photofy_imageLoadCount];
                                var photofy_image = new Image();
                                photofy_image.alt = "Photofy Image";
                                photofy_image.src = photofy_currentImage.ImageUrl;
                                photofy_imageCache.push(photofy_image);
                                photofy_imageList.push(photofy_currentImage);
                                photofy_imageLoadCount++;
                            }
                        }
                        else {
                            clearInterval(cacheTicker);
                        }
                    }
                }
                function photofy() {
                    if (options.previewPause && obj.find(".photofy_overlay").length || photofy_highlightPause) {
                        // Do nothing
                    }
                    else {
                        options.update();
                        var canRandomise = false;
                        photofy_changeOrderList = photofy_changeOrderList.sort(function () { return 0.5 - Math.random() });
                        if (photofy_currentImageIndex >= photofy_imageCount) {
                            photofy_currentImageIndex = 0;
                            canRandomise = true;
                            photofyShuffle();
                        }
                        for (var i = 0; i < options.maxImages; i++) {
                            photofyUpdate(photofy_changeOrderList[i], photofy_imageList[photofy_currentImageIndex], $(obj.find(".photofy_thumbnail").get()[i]));
                            photofy_currentImageIndex++;
                            if (photofy_currentImageIndex == photofy_imageCount) {
                                photofy_currentImageIndex = 0;
                                canRandomise = true;
                            }
                        }
                        if (canRandomise) {
                            photofyShuffle();
                        }
                    }
                    photofy_interval = setTimeout(photofy, (options.maxImages * options.changeTimeout) + options.fadeDuration + options.delay);
                }
                function photofyShuffle() {
                    if (options.shuffle) {
                        photofy_imageList = photofy_imageList.sort(function () { return 0.5 - Math.random() });
                    }
                }
                function photofyUpdate(index, photofyImage, thumbnail) {
                    setTimeout(function () {
                        thumbnail.find("img").fadeTo(options.fadeDuration, 0, function () {
                            thumbnail.attr("href", photofyImage.LinkUrl);
                            thumbnail.find(".photofy_html").html(photofyImage.HTML);
                            $(this).attr("src", photofyImage.ImageUrl);
                            $(this).fadeTo(options.fadeDuration, 1);
                        });
                    }, index * options.changeTimeout);
                }
                function photofyCheckReady() {
                    if (photofyImageFullyLoadedCount < options.maxImages) {
                        photofyImageFullyLoadedCount++;
                        if (photofyImageFullyLoadedCount == options.maxImages) {
                            photofy_interval = setTimeout(photofy, options.delay);
                            options.ready();
                        }
                    }
                }
            });
        },
        stop: function () {
            clearTimeout(photofy_interval);
            this.children().remove();
        }
    };

    $.fn.photofy = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist in Photofy');
        }
    };
})(jQuery);
