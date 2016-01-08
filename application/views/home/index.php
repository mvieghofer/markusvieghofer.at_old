<ul class="navLinks">
    <li><a href="#social" class="navLink" id="navSocial">#social</a></li>
    <li><a href="#skills" class="navLink" id="navSkills">#skills</a></li>
    <li><a href="#blog" class="navLink" id="navBlog">#blog</a></li>
</ul>
<div class="indicator arrow bounce"></div>
<section id="main">
    <div class="container">
        <div id="meText">I'm Markus Vieghofer</div>
        <div id="descriptionWrapper">
            <div class="visible">
                <p>I'm <span id="description">a developer</span></p>
            </div>
        </div>

    </div>
</section>
<section id="social" class="subsection">
    <h2>#social</h2>
    <ul class="socialLinks">
        <li><a href="https://twitter.com/mvieghofer" target="_blank" class="twitter"><div>Twitter</div></a></li>
        <li><a href="https://github.com/mvieghofer" target="_blank" class="github"><div>Github</div></a></li>
        <li><a href="https://at.linkedin.com/in/mvieghofer" target="_blank" class="linkedIn"><div>LinkedIn</div></a></li>
    </ul>
</section>
<section id="skills" class="subsection">
    <h2>#skills</h2>
    <div class="skillSection">
        <h3>Programming Languages</h3>
        <div class="skillList">
            <div class="skill">
                <div class="skillEntry">Java</div>
                <div class="skillEntry"><div class="rating-container"><div class="rating advanced"></div></div></div>
            </div>
            <div class="skill">
                <div class="skillEntry">Android</div>
                <div class="skillEntry"><div class="rating-container"><div class="rating advanced"></div></div></div>
            </div>
            <div class="skill">
                <div class="skillEntry">PHP</div>
                <div class="skillEntry"><div class="rating-container"><div class="rating advanced"></div></div></div>
            </div>
            <div class="skill">
                <div class="skillEntry">HTML</div>
                <div class="skillEntry"><div class="rating-container"><div class="rating advanced"></div></div></div>
            </div>
            <div class="skill">
                <div class="skillEntry">JavaScript</div>
                <div class="skillEntry"><div class="rating-container"><div class="rating medium"></div></div></div>
            </div>
            <div class="skill">
                <div class="skillEntry">CSS</div>
                <div class="skillEntry"><div class="rating-container"><div class="rating medium"></div></div></div>
            </div>
            <div class="skill">
                <div class="skillEntry">C#</div>
                <div class="skillEntry"><div class="rating-container"><div class="rating medium"></div></div></div>
            </div>
        </div>
    </div>
    <div class="skillSection">
        <h3>Build Tools</h3>
        <div class="skillList">
            <div class="skill">
                <div class="skillEntry">Maven</div>
                <div class="skillEntry"><div class="rating-container"><div class="rating advanced"></div></div></div>
            </div>
            <div class="skill">
                <div class="skillEntry">Gradle</div>
                <div class="skillEntry"><div class="rating-container"><div class="rating medium"></div></div></div>
            </div>
        </div>
    </div>
    <div class="skillSection">
        <h3>Frameworks</h3>
        <div class="skillList">
            <div class="skill">
                <div class="skillEntry">Hibernate</div>
                <div class="skillEntry"><div class="rating-container"><div class="rating advanced"></div></div></div>
            </div>
            <div class="skill">
                <div class="skillEntry">ElasticSearch</div>
                <div class="skillEntry"><div class="rating-container"><div class="rating medium"></div></div></div>
            </div>
        </div>
    </div>
    <div class="skillSection">
        <h3>Misc</h3>
        <div class="skillList">
            <div class="skill">
                <div class="skillEntry">SQL</div>
                <div class="skillEntry"><div class="rating-container"><div class="rating advanced"></div></div></div>
            </div>
            <div class="skill">
                <div class="skillEntry">XML</div>
                <div class="skillEntry"><div class="rating-container"><div class="rating advanced"></div></div></div>
            </div>
            <div class="skill">
                <div class="skillEntry">Design Patterns</div>
                <div class="skillEntry"><div class="rating-container"><div class="rating advanced"></div></div></div>
            </div>
        </div>
    </div>
</section>
<section id="blog" class="subsection">
    <h2>#blog</h2>
    <div class="blogPosts js-blogPosts">
        <? foreach($data as $obj) { ?>
            <div class="blogPost" style="background: url(<?= $obj['image'] ?>)" data-id="<?= $obj['id'] ?>">
                <a class="blogPost-link" target="_blank" title="<?= $obj['title'] ?>" href="<?= $obj['url'] ?>">
                    <?= $obj['title'] ?>
                </a>
            </div>
        <? } ?>
    </div>
</section>
<script type="text/javascript" src="/public/javascript/main.js"></script>
