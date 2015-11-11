<? if(isset($this->navItems)) { ?>
    <nav id="nav" class="fixed">
        <ul>
            <? foreach($this->navItems as $navItem) { ?>
                <li><a href="<?=$navItem['url'] ?>"><?= $navItem['text'] ?></a></li>
            <? } ?>
        </ul>
    </nav>
<? } ?>