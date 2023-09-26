/*

minimum structure (items don't have to be direct children of gallery and don't have to be siblings):

<div data-fn="photoswipe">
    ...
    <figure>
        <a href="large-img-001-720x720.jpg" itemprop="contentUrl" data-size="720x720">
            <img src="large-img-001-720x720-thumb.jpg" alt="Image 1">
        </a>
    </figure>
    ...
    <div>
        ...
        <div>
            ...
            <figure>
                <a href="large-img-002-1440x720.jpg" itemprop="contentUrl" data-size="1440x720">
                    <img src="large-img-002-1440x720-thumb.jpg" alt="Image 2">
                </a>
            </figure>
            ...
        </div>
        ...
    </div>
    ...
</div>

one-item-gallery (gallery is item):

<figure data-fn="photoswipe">
    <a href="large-img-001-720x720.jpg" itemprop="contentUrl" data-size="720x720">
        <img src="large-img-001-720x720-thumb.jpg" alt="Image 1">
    </a>
</figure>


better readable structure (rich snippets):

<div itemscope itemtype="http://schema.org/ImageGallery" data-fn="photoswipe">
    ...
    <figure itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
        <a href="large-img-001-720x720.jpg" itemprop="contentUrl" data-size="720x720">
            <img src="large-img-001-720x720-thumb.jpg" alt="Image 1">
        </a>
        <figcaption class="sr-only" itemprop="caption description">Caption 1</figcaption>
    </figure>
    ...
    <div>
        ...
        <div>
            ...
            <figure itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
                <a href="large-img-002-1440x720.jpg" itemprop="contentUrl" data-size="1440x720">
                    <img src="large-img-002-1440x720-thumb.jpg" alt="Image 2">
                </a>
                <figcaption class="sr-only" itemprop="caption description">Caption 1</figcaption>
            </figure>
            ...
        </div>
        ...
    </div>
    ...
</div>


pswp template:

<!-- PHOTOSWIPE SHADOWBOX TEMPLATE -->

<!-- Root element of PhotoSwipe. Must have class pswp. -->
<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
    <!-- Background of PhotoSwipe. 
         It's a separate element as animating opacity is faster than rgba(). -->
    <div class="pswp__bg"></div>
    <!-- Slides wrapper with overflow:hidden. -->
    <div class="pswp__scroll-wrap">
        <!-- Container that holds slides. 
            PhotoSwipe keeps only 3 of them in the DOM to save memory.
            Don't modify these 3 pswp__item elements, data is added later on. -->
        <div class="pswp__container">
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
        </div>
        <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
        <div class="pswp__ui pswp__ui--hidden">
            <div class="pswp__top-bar">
                <!--  Controls are self-explanatory. Order can be changed. -->
                <div class="pswp__counter"></div>
                <button class="pswp__button pswp__button--close" title="Close (Esc)"><span><i class="fa fa-close" aria-hidden="true"></i><span class="sr-only">&nbsp;</span></span></button>
                <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"><i class="fa fa-arrows-alt" aria-hidden="true"></i></button>
                <button class="pswp__button pswp__button--zoom" title="Zoom in/out"><i class="fa fa-search-plus" aria-hidden="true"></i></button>
                <!-- Preloader demo http://codepen.io/dimsemenov/pen/yyBWoR -->
                <!-- element will get class pswp__preloader- -active when preloader is running -->
                <div class="pswp__preloader">
                    <div class="pswp__preloader__icn">
                      <i class="fa fa-circle-o-notch" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
            <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                <div class="pswp__share-tooltip"></div> 
            </div>
            <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"><i class="fa fa-arrow-left" aria-hidden="true"></i></button>
            <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"><i class="fa fa-arrow-right" aria-hidden="true"></i></button>
            <div class="pswp__caption">
                <div class="pswp__caption__center container text-center"></div>
            </div>
        </div>
    </div>
</div>

*/

/*
requires:
    '../../../node_modules/photoswipe/dist/photoswipe.js'
    '../../../node_modules/photoswipe/dist/photoswipe-ui-default.js'
*/


import $ from "jquery"
import Utils from './../../js/leg-utils/utils/utils'



// photoswipe

var initPhotoSwipeFromDOM = function(gallerySelector) {

    // add lazyload src identifier
    var lazyloadSrcAttr = 'data-scr';
    var placeholderSrcString = 'base64';
    var itemNodeName = 'FIGURE';

    function elemIs( el, selector ) {
        var matchesFn;
        // find vendor prefix
        [ 'matches', 'webkitMatchesSelector', 'mozMatchesSelector', 'msMatchesSelector', 'oMatchesSelector' ].some( function( fn ) {
            if ( typeof document.body[ fn ] == 'function' ) {
                matchesFn = fn;
                return true;
            }
            return false;
        } );
        if ( el[ matchesFn ]( selector ) ) {
            return true;
        }
        else {
            return false;
        }
    }

    function closestElem(el, selector) {
        var parent;
        // traverse parents
        while (el) {
            parent = el.parentElement;
            // if (parent && parent[matchesFn](selector)) {
            if ( parent && elemIs( parent, selector ) ) {
                return parent;
            }
            el = parent;
        }
        return null;
    }

    // parse slide data (url, title, size ...) from DOM elements 
    // (children of gallerySelector)
    var parseThumbnailElements = function(el) {
        // find itemNodeName (FIGURE) elems
        var thumbElements = 
                elemIs( el, itemNodeName ) 
                ? [ el ]
                : el.getElementsByTagName( itemNodeName )
            ,
            numNodes = thumbElements.length,
            items = [],
            figureEl,
            linkEl,
            size,
            item;

        for(var i = 0; i < numNodes; i++) {

            figureEl = thumbElements[i]; // <figure> element

            // include only element nodes – customize: allow non thumb elements as siblings of thumbs
            if( figureEl.nodeType !== 1 || figureEl.nodeName.toUpperCase() !== itemNodeName ) {
                continue;
            }

            linkEl = figureEl.children[0]; // <a> element

            size = linkEl.getAttribute('data-size').split('x');

            // create slide object
            item = {
                src: linkEl.getAttribute('href'),
                w: parseInt(size[0], 10),
                h: parseInt(size[1], 10)
            };

            if(figureEl.children.length > 1) {
                // <figcaption> content
                item.title = figureEl.children[1].innerHTML; 
            }

            if(linkEl.children.length > 0) {
                // <img> thumbnail element, retrieving thumbnail url

                // custom adaption: src might be empty or lazyload placeholder, therefor get src from lazyload src attr

                var thumb = linkEl.getElementsByTagName( 'img' )[0];

                item.msrc = thumb.getAttribute( 'src' );

                if ( ( item.msrc == '' || item.msrc.indexOf( placeholderSrcString ) > -1 ) && !! thumb.getAttribute( lazyloadSrcAttr ) ) {
                    // get src from lazyload src attr
                    item.msrc = thumb.getAttribute( lazyloadSrcAttr )
                }
                
            } 

            item.el = figureEl; // save link to element for getThumbBoundsFn
            items.push(item);
        }

        return items;
    };

    // find nearest parent element
    var closest = function closest(el, fn) {
        return el && ( fn(el) ? el : closest(el.parentNode, fn) );
    };

    // triggers when user clicks on thumbnail
    var onThumbnailsClick = function(e) {
        e = e || window.event;
        e.preventDefault ? e.preventDefault() : e.returnValue = false;

        var eTarget = e.target || e.srcElement;

        // find root element of slide
        var clickedListItem = 
            elemIs( eTarget, itemNodeName )
            ? eTarget
            : closest( eTarget, function( el ) {
                return ( el.tagName && el.tagName.toUpperCase() === itemNodeName );
            } )
        ;

        if(!clickedListItem) {
            return;
        }

        // find index of clicked item by looping through all child nodes
        // alternatively, you may define index via data- attribute

        // clickedGallery might be clickedListItem itself or any parent (find closest parent with gallerySelector then)
        var itemIsGallery = elemIs( clickedListItem, gallerySelector ),
            clickedGallery = 
                itemIsGallery 
                ? clickedListItem
                : closestElem( clickedListItem, gallerySelector )
            ,
            // find all itemNodeName (FIGURE) elems
            childNodes = 
                itemIsGallery 
                ? [ clickedListItem ]
                : clickedGallery.getElementsByTagName( itemNodeName )
            ,
            numChildNodes = childNodes.length,
            nodeIndex = 0,
            index;

        for (var i = 0; i < numChildNodes; i++) {
            if(childNodes[i].nodeType !== 1) { 
                continue; 
            }

            if(childNodes[i] === clickedListItem) {
                index = nodeIndex;
                break;
            }
            nodeIndex++;
        }

        if(index >= 0) {
            // open PhotoSwipe if valid index found
            openPhotoSwipe( index, clickedGallery );
        }
        return false;
    };

    // parse picture index and gallery index from URL (#&pid=1&gid=2)
    var photoswipeParseHash = function() {
        var hash = window.location.hash.substring(1),
        params = {};

        if(hash.length < 5) {
            return params;
        }

        var vars = hash.split('&');
        for (var i = 0; i < vars.length; i++) {
            if(!vars[i]) {
                continue;
            }
            var pair = vars[i].split('=');  
            if(pair.length < 2) {
                continue;
            }           
            params[pair[0]] = pair[1];
        }

        if(params.gid) {
            params.gid = parseInt(params.gid, 10);
        }

        return params;
    };

    var openPhotoSwipe = function(index, galleryElement, disableAnimation, fromURL) {
        // TODO: '.pswp' doesn't exist, does this cause any problems?
        var pswpElement = document.querySelectorAll('.pswp')[0],
            gallery,
            options,
            items;

        items = parseThumbnailElements(galleryElement);

        // define options (if needed)
        options = {

            // define gallery index (for URL)
            galleryUID: galleryElement.getAttribute('data-pswp-uid'),

            getThumbBoundsFn: function(index) {
                // See Options -> getThumbBoundsFn section of documentation for more info
                var thumbnail = items[index].el.getElementsByTagName('img')[0], // find thumbnail
                    pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
                    rect = thumbnail.getBoundingClientRect(); 

                return {x:rect.left, y:rect.top + pageYScroll, w:rect.width};
            },

            history: false

        };

        // PhotoSwipe opened from URL
        if(fromURL) {
            if(options.galleryPIDs) {
                // parse real index when custom PIDs are used 
                // http://photoswipe.com/documentation/faq.html#custom-pid-in-url
                for(var j = 0; j < items.length; j++) {
                    if(items[j].pid == index) {
                        options.index = j;
                        break;
                    }
                }
            } else {
                // in URL indexes start from 1
                options.index = parseInt(index, 10) - 1;
            }
        } else {
            options.index = parseInt(index, 10);
        }

        // exit if index not found
        if( isNaN(options.index) ) {
            return;
        }

        if(disableAnimation) {
            options.showAnimationDuration = 0;
        }

        // Pass data to PhotoSwipe and initialize it
        gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);
        gallery.init();
    };

    // loop through all gallery elements and bind events
    var galleryElements = document.querySelectorAll( gallerySelector );

    for(var i = 0, l = galleryElements.length; i < l; i++) {
        galleryElements[i].setAttribute('data-pswp-uid', i+1);
        galleryElements[i].onclick = onThumbnailsClick;
    }

    // Parse URL and open gallery if it contains #&pid=3&gid=1
    var hashData = photoswipeParseHash();
    if(hashData.pid && hashData.gid) {
        openPhotoSwipe( hashData.pid ,  galleryElements[ hashData.gid - 1 ], true, true );
    }
};

// execute above function
initPhotoSwipeFromDOM( '[' + Utils.attributes.functionElement + '~="photoswipe"]' );

// /photoswipe

