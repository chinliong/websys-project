document.addEventListener("DOMContentLoaded", function () {
    // Code to be executed when the DOM is ready (i.e., the document is fully loaded):
    registerEventListeners(); // You need to write this function...
});

function registerEventListeners() {
    var thumbnails = document.querySelectorAll('.img-thumbnail');

    thumbnails.forEach(function (thumbnail) {
        thumbnail.addEventListener('click', function () {
            var imageSrc = thumbnail.src;
           // console.log('Clicked image source:', imageSrc);

            var imageName = getImageName(imageSrc);

            displayBiggerImage(imageName);
        });
    });
}

function getImageName(imageSrc) {
    //This function gets the name of the image [e.g.: calico_small.jpg -> calico]
    var parts = imageSrc.split('/');
    var filename = parts[parts.length - 1];
    var imageName = filename.split('.')[0];
    imageName = imageName.replace('_small', '');
    return imageName;
}

function displayBiggerImage(imageName) {
    var biggerImageSrc = 'images/' + imageName + '_large.jpg'; 

    var overlay = document.createElement('div');
    overlay.classList.add('overlay');

    var contentContainer = document.createElement('div');
    contentContainer.classList.add('content-container');

    var biggerImage = document.createElement('img');
    biggerImage.src = biggerImageSrc;
    biggerImage.classList.add('bigger-image');

    var caption = document.createElement('p');
    caption.classList.add("caption");
    caption.textContent = imageName;

    contentContainer.appendChild(biggerImage);
    contentContainer.appendChild(caption);

    overlay.appendChild(contentContainer);
    document.body.appendChild(overlay);

    overlay.addEventListener('click', function (event) {
        // Check if the click target is the image itself
        if (event.target === biggerImage) {
            // If it is, remove the overlay
            document.body.removeChild(overlay);
        }
    });
}

$(document).ready(function() {
    $("#editProfileBtn").click(function() {
        $("#editProfileForm").slideToggle();
    });
});
