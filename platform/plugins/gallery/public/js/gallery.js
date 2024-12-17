// lightGallery(document.getElementById('lightgallery'));

var lg = document.querySelectorAll( '.lg-gallery');
for ( var i = 0; i < lg.length; i++ ) {
    lightGallery( lg[i], {
        // selector: '.gallery-item > a:has(img)',
        // mode: 'lg-fade',
        // preload: 2,
        // counter: false,
        // download: false
    });
}
