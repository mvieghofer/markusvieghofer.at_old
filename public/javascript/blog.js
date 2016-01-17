ghost.init({
  clientId: "ghost-frontend",
  clientSecret: "7c09e31b412f"
});

var baseUrl = "http://devcouch.net";

function setBlogPosts(blogPosts) {
    var blogPostsContainer = document.getElementsByClassName('js-blogPosts')[0],
        children = blogPostsContainer.children;
    if (children.length === 0 || children.item(0).dataset.id !== blogPosts[0].id) {
        removeAllChildren(blogPostsContainer);
        for (var i = 0; i < blogPosts.length; i++) {
            var blogPost = blogPosts[i];
            var div = document.createElement('div');
            div.className = 'blogPost';
            div.style.background = "url('" + getImageLink(blogPost.image) + "')";
            div.style.backgroundSize = "cover";
            div.style.backgroundPosition = "center";
            div.style.backgroundRepeat = "no-repeat";
            div.dataset.id = blogPost.id;
            var a = document.createElement('a');
            var linkText = document.createTextNode(blogPost.title);
            a.className = 'blogPost-link';
            a.target = '_blank';
            a.appendChild(linkText);
            a.title = blogPost.title;
            a.href = getBlogPostUrl(blogPost.url);
            div.appendChild(a);
            blogPostsContainer.appendChild(div);
        }
    }
}

function getBlogPostUrl(url) {
    if (url.indexOf("http://") !== 0 && url.indexOf("https://") !== 0) {
        url = baseUrl + url;
    }
    return url;
}

function getImageLink(image) {
    if (image.indexOf("http://") !== 0 && image.indexOf("https://") !== 0) {
        image = baseUrl + image;
    } else if (image.indexOf("http://res.cloudinary.com") === 0) {
        var upload = "upload",
            index = image.lastIndexOf(upload),
            first = image.substring(0, index + upload.length);
            last = image.substring(index + upload.length, image.length),
            image = first + "/h_169,w_300" + last;
        console.log(image);
    }
    return image;
}

function removeAllChildren(container) {
    while(container.firstChild) {
        container.removeChild(container.firstChild);
    }
}

function getBlogPosts() {
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function(data) {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            try {
                var jsonData = JSON.parse(xmlHttp.responseText);
                setBlogPosts(jsonData.posts);
            } catch (ex) {
                // retry in 5 seconds
                setTimeout(getBlogPosts, 5000);
            }
        }
    };
    xmlHttp.open("GET", ghost.url.api('posts', {'limit': 5}), true);
    xmlHttp.send();
}

getBlogPosts();
