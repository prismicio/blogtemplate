$(function(){

    "use strict";

    // Slides

    function goToPreviousSlide(e) {
        var $slide = $(this).parents('.slide');
        var $slides = $slide.parents('.slides');
        var $prev = $slide.prev();
        if (!$prev[0]) {
            $prev = $slides.find('.slide').last();
        }
        $slide.removeClass('active');
        $prev.addClass('active');
        e.preventDefault();
    }

    function goToNextSlide(e) {
        var $slide = $(this).parents('.slide');
        var $slides = $slide.parents('.slides');
        var $next = $slide.next();
        if (!$next[0]) {
            $next = $slides.find('.slide').first();
        }
        $slide.removeClass('active');
        $next.addClass('active');
        e.preventDefault();
    }

    $('.slides .slide:first-child').addClass('active');
    $('.slides .slide a.arrow-prev').on('click', goToPreviousSlide);
    $('.slides .slide a.arrow-next').on('click', goToNextSlide);

    // Preview

    function selectPreviewDoc() {
        var $previewPane = $(this).parents('.featured-preview').find('.preview-pane');
        var imageUrl = $(this).data('illustration');
        $previewPane.find('img').attr('src', imageUrl);
    }

    $('.featured-preview .group-doc').on('click', selectPreviewDoc);
    $('.featured-preview .group-doc').first().map(selectPreviewDoc);
});
