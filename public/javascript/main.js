window.onload = function() {
    if (window.location.hash) {
        smoothScrollToAnchor();
    }
    addScrollIndicatorListener();
    addNavLinksClickListener();
    startDescriptionAnimation();
    getBlogPosts();
};

var animationIndex = 1;
var animate = true;

window.onfocus = function() {
    animate = true;
    animationIndex++;
    startDescriptionAnimation();
}

window.onblur = function() {
    animate = false;
}

function addNavLinksClickListener() {
    var navLinks = document.getElementsByClassName('navLink');
    var i = 0;
    for (i = 0; i < navLinks.length; i++) {
        var navLink = navLinks[i];
        navLink.addEventListener("click", navLinkClickListener);
    };
}

function addScrollIndicatorListener() {
    var scrollIndicator = document.getElementsByClassName('indicator')[0],
        classNames = scrollIndicator.className,
        upClass = "up";
    checkScrollIndicator(scrollIndicator, classNames, upClass);
    document.onscroll = function() {
        checkScrollIndicator(scrollIndicator, classNames, upClass);
    }
}

function checkScrollIndicator(scrollIndicator, classNames, upClass) {
    if (document.body.scrollTop == 0) {
        scrollIndicator.className = removeClassName(scrollIndicator, upClass);
        scrollIndicator.onclick = emptyScrollListener;
    } else if (classNames.indexOf(upClass, classNames.length - upClass.length) === -1) {
        scrollIndicator.className = classNames + " " +  upClass;
        scrollIndicator.onclick = scrollToTop;
    }
}

function removeClassName(element, className) {
    var classNames = element.className,
        indexOfClass = classNames.indexOf(className),
        newClassNames = classNames;
    if (indexOfClass > 0) {
        var newClassNames = classNames.substring(0, indexOfClass);
        console.log(indexOfClass);
        if (indexOfClass + className.length < classNames.length) {
            newClassNames += classNames.substring(indexOfClass + className.length, classNames.length);
        }
    }
    return newClassNames.trim();
}

function emptyScrollListener() {
    return false;
}

function scrollToTop() {
    var scrollPosition = 0,
        duration = 200,
        id = '';
    scrollTo(document.body, scrollPosition, duration, id);
}

function startDescriptionAnimation() {
    var descriptions = [
        'a developer',
        'a father',
        'a free athlete',
        'a boyfriend',
        'a geek',
        'a dog person'
    ];
    setTimeout(startDescriptionAnimationImpl, 3000, descriptions);
}

function startDescriptionAnimationImpl(descriptions) {
    if (animationIndex >= descriptions.length) {
        animationIndex = 0;
    }
    var newDescriptionText = descriptions[animationIndex],
        description = document.getElementById('description'),
        duration = 200,
        descriptionWrapper = document.getElementById('descriptionWrapper'),
        lineHeight = window.getComputedStyle(descriptionWrapper).getPropertyValue('line-height');
    lineHeight = lineHeight.substring(0, lineHeight.length - 2);
    var steps = 100,
        stepSize = lineHeight / steps,
        sleepTime = duration / steps;
    animateDescription(description, 0, stepSize, sleepTime, steps, newDescriptionText);
    if (animate) {
        setTimeout(startDescriptionAnimationImpl, 3000, descriptions, ++animationIndex);
    }
}

function animateDescription(description, step, stepSize, sleepTime, maxSteps, newDescriptionText) {
    var top = description.style.top;
    if (top.indexOf('px') > 0) {
        top = top.substring(0, top.length - 2);
    } else {
        top = 0;
    }
    description.style.top = (parseFloat(top) + stepSize) + "px";
    if (step < maxSteps) {
        setTimeout(animateDescription, sleepTime, description, ++step, stepSize, sleepTime, maxSteps, newDescriptionText);
    } else if (step == maxSteps) {
        var newText = document.createTextNode(newDescriptionText);
        description.removeChild(description.firstChild);
        description.appendChild(newText);
        description.style.top = "-" + (stepSize * maxSteps) + "px";
        setTimeout(animateDescription, sleepTime, description, ++step, stepSize, sleepTime, maxSteps, newDescriptionText);
    } else if (step < (maxSteps * 2)) {
        setTimeout(animateDescription, sleepTime, description, ++step, stepSize, sleepTime, maxSteps, newDescriptionText);
    }
}

function getBlogPosts() {
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function() {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            try {
                var jsonData = JSON.parse(xmlHttp.responseText);
                setBlogPosts(jsonData);
            } catch (ex) {
                // retry in 5 seconds
                setTimeout(getBlogPosts, 5000);
            }
        }
    }
    xmlHttp.open("GET", '/home/get-blog-posts', true);
    xmlHttp.send();
}

function setBlogPosts(blogPosts) {
    var blogPostsContainer = document.getElementsByClassName('js-blogPosts')[0],
        children = blogPostsContainer.children;
    if (children.length === 0 || children.item(0).dataset.id !== blogPosts[0].id) {
        removeAllChildren(blogPostsContainer);
        for (var i = 0; i < blogPosts.length; i++) {
            var blogPost = blogPosts[i];
            var div = document.createElement('div');
            div.className = 'blogPost';
            div.style.background = "url('" + blogPost.image + "')";
            console.log(div);
            div.dataset.id = blogPost.id;
            var a = document.createElement('a');
            var linkText = document.createTextNode(blogPost.title);
            a.className = 'blogPost-link';
            a.target = '_blank';
            a.appendChild(linkText);
            a.title = blogPost.title;
            a.href = blogPost.url;
            div.appendChild(a);
            blogPostsContainer.appendChild(div);
        }
    }
}

function removeAllChildren(container) {
    while(container.firstChild) {
        container.removeChild(container.firstChild);
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
