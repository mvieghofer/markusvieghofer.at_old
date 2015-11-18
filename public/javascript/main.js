window.onload = function() {
    if (window.location.hash) {
        smoothScrollToAnchor();
    }

    getBlogPosts();
};

(function() {
    var navLinks = document.getElementsByClassName('navLink');
    var i = 0;
    for (i = 0; i < navLinks.length; i++) {
        var navLink = navLinks[i];
        navLink.addEventListener("click", navLinkClickListener);
    };
})();

function getBlogPosts() {
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function() {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            console.log(xmlHttp.responseText);
            setBlogPosts(JSON.parse(xmlHttp.responseText));
        }
    }
    xmlHttp.open("GET", '/home/get-blog-posts', true);
    xmlHttp.send();
}

function setBlogPosts(blogPosts) {
    var blogPostsContainer = document.getElementsByClassName('js-blogPosts')[0];
    for (var i = 0; i < blogPosts.length; i++) {
        var blogPost = blogPosts[i];
        var div = document.createElement('div');
        div.className = 'blogPost';
        div.style.background = "url('" + blogPost.image + "')";
        var a = document.createElement('a');
        var linkText = document.createTextNode(blogPost.title);
        a.className = 'blogPost-link';
        a.appendChild(linkText);
        a.title = blogPost.title;
        a.href = blogPost.link;
        div.appendChild(a);
        blogPostsContainer.appendChild(div);
    }
}

function smoothScrollToAnchor() {
    var id = window.location.hash;
    window.location.hash = '';
    id = id.substring(1, id.length);
    var elem = document.getElementById(id),
        duration = 200,
        scrollPosition = elem.offsetTop;
    scrollTo(document.body, scrollPosition, duration, id);
}

function navLinkClickListener(event) {
    event.preventDefault();
    var id = event.target.hash;
    id = id.substring(1, id.length);
    var elem = document.getElementById(id);
    var duration = 500; //ms
    var scrollPosition = elem.offsetTop;
    scrollTo(document.body, scrollPosition, duration, id);
}

function scrollTo(element, to, duration, id) {
    var start = element.scrollTop,
        change = to - start,
        currentTime = 0,
        increment = 20;

    var success = function() {
        window.location.hash = id;
    };
    var animateScroll = function(){
        currentTime += increment;
        var val = easeInOutQuad(currentTime, start, change, duration);
        element.scrollTop = val;
        if(currentTime < duration) {
            setTimeout(animateScroll, increment);
        } else {
            success();
        }
    };
    animateScroll();
}

//t = current time
//b = start value
//c = change in value
//d = duration
function easeInOutQuad(t, b, c, d) {
	t /= d/2;
	if (t < 1) return c/2*t*t + b;
	t--;
	return -c/2 * (t*(t-2) - 1) + b;
};
